<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Authentication;

use Skitlabs\Bayeux\Authentication\Exception\AuthenticationException;

interface Authentication
{
    /**
     * Attempt to authenticate once, if required. Return
     * the stored authentication token otherwise.
     *
     * @throws AuthenticationException
     */
    public function token() : string;

    /**
     * Attempt to authenticate once, if required. Return
     * the stored authentication token-type otherwise.
     *
     * @throws AuthenticationException
     */
    public function tokenType() : string;

    /**
     * Reset authentication state, essentially forgetting
     * the current authenticated user.
     */
    public function reset() : void;
}
