<?php

declare(strict_types=1);

namespace Welfare\Verification\Contracts;

use Welfare\Verification\DTO\VerificationResult;

interface VerificationDriverInterface
{
    /**
     * Verify Bank Verification Number (BVN).
     *
     * @param string $bvn 11-digit BVN
     * @param string|null $selfieUrl Optional image URL for facial matching
     * @param array $extra Additional params (e.g. date_of_birth)
     */
    public function verifyBvn(string $bvn, ?string $selfieUrl = null, array $extra = []): VerificationResult;

    /**
     * Verify National Identification Number (NIN).
     *
     * @param string $nin 11-digit NIN
     * @param array $extra Additional params
     */
    public function verifyNin(string $nin, array $extra = []): VerificationResult;

    /**
     * Verify Corporate Affairs Commission (CAC) business/company details.
     *
     * @param string $registrationNumber RC or BN number
     * @param string $companyType enterprise | company | etc.
     * @param array $extra Additional params
     */
    public function verifyBusiness(string $registrationNumber, string $companyType = 'company', array $extra = []): VerificationResult;

    /**
     * Verify Driver's License.
     *
     * @param string $licenseNumber
     * @param string|null $dateOfBirth
     * @param array $extra
     */
    public function verifyDriversLicense(string $licenseNumber, ?string $dateOfBirth = null, array $extra = []): VerificationResult;

    /**
     * Verify International Passport.
     *
     * @param string $passportNumber
     * @param string|null $lastName
     * @param string|null $dateOfBirth
     * @param array $extra
     */
    public function verifyPassport(string $passportNumber, ?string $lastName = null, ?string $dateOfBirth = null, array $extra = []): VerificationResult;

    /**
     * Verify residential / business address.
     *
     * @param array $addressDetails
     */
    public function verifyAddress(array $addressDetails): VerificationResult;
}
