# Welfare Verification Package

Multi-provider customer identity, KYC, and corporate CAC verification engine for Laravel applications (used by both Welfare MFB and Core Banking).

## Features
- **BVN Verification**: Standard and facial match via IdentityPass (Prembly).
- **NIN Verification**: National Identity Number verification.
- **CAC Business Verification**: Corporate Affairs Commission advance lookup (validates active status, company type, RC/BN number, registered directors).
- **Government ID Verification**: Drivers license and international passport.
- **Address Verification**: Geocoding and residential address checks.
- **Extensible Driver Architecture**: Seamlessly swap or add providers (IdentityPass, QoreID, Dojah, Smile Identity).

## Usage
```php
use Welfare\Verification\Facades\Verification;

// Verify BVN
$result = Verification::verifyBvn('12345678901');
if ($result->isSuccessful()) {
    $data = $result->data;
}

// Verify CAC Business
$result = Verification::verifyBusiness('RC123456', 'company');
if ($result->isSuccessful()) {
    $companyName = $result->data['company_name'];
}
```
