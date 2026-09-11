<?php

declare(strict_types=1);

namespace Welfare\Verification\Drivers;

use Welfare\Verification\Contracts\VerificationDriverInterface;
use Welfare\Verification\DTO\VerificationResult;

class NullDriver implements VerificationDriverInterface
{
    public function __construct(private readonly array $config = []) {}

    public function verifyBvn(string $bvn, ?string $selfieUrl = null, array $extra = []): VerificationResult
    {
        return VerificationResult::success(
            message: 'BVN verified successfully (Simulated)',
            data: [
                'bvn' => $bvn,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => $extra['date_of_birth'] ?? '1990-01-01',
                'phone_number' => '08012345678',
            ],
            provider: 'null',
            reference: 'SIM-BVN-' . bin2hex(random_bytes(4))
        );
    }

    public function verifyNin(string $nin, array $extra = []): VerificationResult
    {
        return VerificationResult::success(
            message: 'NIN verified successfully (Simulated)',
            data: [
                'nin' => $nin,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'male',
            ],
            provider: 'null',
            reference: 'SIM-NIN-' . bin2hex(random_bytes(4))
        );
    }

    public function verifyBusiness(string $registrationNumber, string $companyType = 'company', array $extra = []): VerificationResult
    {
        return VerificationResult::success(
            message: 'CAC Business verified successfully (Simulated)',
            data: [
                'rc_number' => $registrationNumber,
                'company_name' => 'ACME ENTERPRISES NIGERIA LIMITED',
                'company_type' => $companyType,
                'status' => 'ACTIVE',
                'registration_date' => '2020-01-15',
                'directors' => [
                    ['name' => 'John Doe', 'designation' => 'DIRECTOR'],
                ],
            ],
            provider: 'null',
            reference: 'SIM-CAC-' . bin2hex(random_bytes(4))
        );
    }

    public function verifyDriversLicense(string $licenseNumber, ?string $dateOfBirth = null, array $extra = []): VerificationResult
    {
        return VerificationResult::success(
            message: 'Drivers license verified successfully (Simulated)',
            data: ['license_number' => $licenseNumber, 'status' => 'VALID'],
            provider: 'null',
            reference: 'SIM-DL-' . bin2hex(random_bytes(4))
        );
    }

    public function verifyPassport(string $passportNumber, ?string $lastName = null, ?string $dateOfBirth = null, array $extra = []): VerificationResult
    {
        return VerificationResult::success(
            message: 'Passport verified successfully (Simulated)',
            data: ['passport_number' => $passportNumber, 'status' => 'VALID'],
            provider: 'null',
            reference: 'SIM-PASS-' . bin2hex(random_bytes(4))
        );
    }

    public function verifyAddress(array $addressDetails): VerificationResult
    {
        return VerificationResult::success(
            message: 'Address verified successfully (Simulated)',
            data: array_merge($addressDetails, ['verification_status' => 'verified']),
            provider: 'null',
            reference: 'SIM-ADDR-' . bin2hex(random_bytes(4))
        );
    }
}
