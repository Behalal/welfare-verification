<?php

declare(strict_types=1);

namespace Welfare\Verification;

use Illuminate\Support\Manager;
use Welfare\Verification\Contracts\VerificationDriverInterface;
use Welfare\Verification\Drivers\IdentityPassDriver;
use Welfare\Verification\Drivers\NullDriver;
use Welfare\Verification\DTO\VerificationResult;

/**
 * @mixin VerificationDriverInterface
 */
class VerificationManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('verification.default', 'identitypass');
    }

    public function createIdentitypassDriver(): VerificationDriverInterface
    {
        $config = $this->config->get('verification.drivers.identitypass', []);
        return new IdentityPassDriver($config);
    }

    public function createNullDriver(): VerificationDriverInterface
    {
        $config = $this->config->get('verification.drivers.null', []);
        return new NullDriver($config);
    }
}
