<?php

declare(strict_types=1);

namespace Welfare\Verification\DTO;

class VerificationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly ?array $data = null,
        public readonly ?string $reference = null,
        public readonly ?string $provider = null,
        public readonly ?array $raw = null,
        public readonly ?string $errorCode = null
    ) {}

    public static function success(string $message, ?array $data = null, ?string $provider = null, ?string $reference = null, ?array $raw = null): self
    {
        return new self(
            success: true,
            message: $message,
            data: $data,
            reference: $reference,
            provider: $provider,
            raw: $raw
        );
    }

    public static function failure(string $message, ?string $errorCode = null, ?string $provider = null, ?array $raw = null): self
    {
        return new self(
            success: false,
            message: $message,
            provider: $provider,
            raw: $raw,
            errorCode: $errorCode
        );
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }

    public function failed(): bool
    {
        return !$this->success;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'reference' => $this->reference,
            'provider' => $this->provider,
            'error_code' => $this->errorCode,
        ];
    }
}
