<?php

declare(strict_types=1);

namespace Welfare\Verification\Facades;

use Illuminate\Support\Facades\Facade;
use Welfare\Verification\VerificationManager;

/**
 * @method static \Welfare\Verification\DTO\VerificationResult verifyBvn(string $bvn, ?string $selfieUrl = null, array $extra = [])
 * @method static \Welfare\Verification\DTO\VerificationResult verifyNin(string $nin, array $extra = [])
 * @method static \Welfare\Verification\DTO\VerificationResult verifyBusiness(string $registrationNumber, string $companyType = 'company', array $extra = [])
 * @method static \Welfare\Verification\DTO\VerificationResult verifyDriversLicense(string $licenseNumber, ?string $dateOfBirth = null, array $extra = [])
 * @method static \Welfare\Verification\DTO\VerificationResult verifyPassport(string $passportNumber, ?string $lastName = null, ?string $dateOfBirth = null, array $extra = [])
 * @method static \Welfare\Verification\DTO\VerificationResult verifyAddress(array $addressDetails)
 * @method static \Welfare\Verification\Contracts\VerificationDriverInterface driver(?string $driver = null)
 *
 * @see \Welfare\Verification\VerificationManager
 */
class Verification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return VerificationManager::class;
    }
}
