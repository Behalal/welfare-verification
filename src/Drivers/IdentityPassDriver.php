<?php

declare(strict_types=1);

namespace Welfare\Verification\Drivers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Welfare\Verification\Contracts\VerificationDriverInterface;
use Welfare\Verification\DTO\VerificationResult;

class IdentityPassDriver implements VerificationDriverInterface
{
    private string $baseUrl;
    private string $apiKey;
    private string $appId;
    private int $timeout;

    public function __construct(array $config)
    {
        $this->baseUrl = rtrim($config['base_url'] ?? 'https://api.myidentitypass.com/', '/') . '/';
        $this->apiKey = (string) ($config['api_key'] ?? '');
        $this->appId = (string) ($config['app_id'] ?? '');
        $this->timeout = (int) ($config['timeout'] ?? 30);
    }

    public function verifyBvn(string $bvn, ?string $selfieUrl = null, array $extra = []): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = $selfieUrl
            ? 'api/v2/biometrics/merchant/data/verification/bvn_w_face'
            : 'api/v2/biometrics/merchant/data/verification/bvn';

        $payload = ['number' => $bvn];
        if ($selfieUrl) {
            $payload['image'] = $selfieUrl;
        }
        if (!empty($extra['date_of_birth'])) {
            $payload['dob'] = $extra['date_of_birth'];
        }

        return $this->sendRequest($endpoint, $payload);
    }

    public function verifyNin(string $nin, array $extra = []): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = 'api/v2/biometrics/merchant/data/verification/nin_wo_face';
        $payload = array_merge(['number' => $nin], $extra);

        return $this->sendRequest($endpoint, $payload);
    }

    public function verifyBusiness(string $registrationNumber, string $companyType = 'company', array $extra = []): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = 'api/v2/biometrics/merchant/data/verification/cac/advance';
        $payload = array_merge([
            'rc_number' => $registrationNumber,
            'company_type' => $companyType,
        ], $extra);

        return $this->sendRequest($endpoint, $payload);
    }

    public function verifyDriversLicense(string $licenseNumber, ?string $dateOfBirth = null, array $extra = []): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = 'api/v2/biometrics/merchant/data/verification/drivers_license/basic';
        $payload = array_merge([
            'number' => $licenseNumber,
            'dob' => $dateOfBirth,
        ], $extra);

        return $this->sendRequest($endpoint, $payload);
    }

    public function verifyPassport(string $passportNumber, ?string $lastName = null, ?string $dateOfBirth = null, array $extra = []): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = 'api/v2/biometrics/merchant/data/verification/national_passport';
        $payload = array_merge([
            'number' => $passportNumber,
            'last_name' => $lastName,
            'dob' => $dateOfBirth,
        ], $extra);

        return $this->sendRequest($endpoint, $payload);
    }

    public function verifyAddress(array $addressDetails): VerificationResult
    {
        if (empty($this->apiKey) || empty($this->appId)) {
            return VerificationResult::failure('IdentityPass credentials not configured.', 'NOT_CONFIGURED', 'identitypass');
        }

        $endpoint = 'api/v2/biometrics/merchant/data/verification/address';
        return $this->sendRequest($endpoint, $addressDetails);
    }

    private function sendRequest(string $endpoint, array $payload): VerificationResult
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'accept' => 'application/json',
                    'x-api-key' => $this->apiKey,
                    'app-id' => $this->appId,
                ])
                ->post($this->baseUrl . $endpoint, $payload);

            $data = $response->json();

            if ($response->successful() && is_array($data)) {
                $status = $data['status'] ?? false;
                $isSuccess = ($status === true || $status === 'success' || ($data['response_code'] ?? null) === '00');
                $message = $data['message'] ?? ($isSuccess ? 'Verification successful' : 'Verification failed');
                $details = $data['data'] ?? $data['response_data'] ?? $data;

                if ($isSuccess) {
                    return VerificationResult::success(
                        message: $message,
                        data: is_array($details) ? $details : ['result' => $details],
                        provider: 'identitypass',
                        reference: (string) ($data['reference'] ?? $data['verification_id'] ?? null),
                        raw: $data
                    );
                }

                return VerificationResult::failure(
                    message: $message,
                    errorCode: (string) ($data['response_code'] ?? 'VERIFICATION_FAILED'),
                    provider: 'identitypass',
                    raw: $data
                );
            }

            $errorMessage = is_array($data) ? ($data['message'] ?? 'IdentityPass verification failed') : 'HTTP request error: ' . $response->status();

            return VerificationResult::failure(
                message: $errorMessage,
                errorCode: (string) $response->status(),
                provider: 'identitypass',
                raw: is_array($data) ? $data : ['body' => $response->body()]
            );
        } catch (\Throwable $e) {
            Log::error('IdentityPass request failed: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'trace' => $e->getTraceAsString(),
            ]);

            return VerificationResult::failure(
                message: 'Identity verification service temporarily unavailable: ' . $e->getMessage(),
                errorCode: 'NETWORK_OR_SERVER_ERROR',
                provider: 'identitypass'
            );
        }
    }
}
