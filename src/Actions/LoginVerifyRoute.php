<?php

namespace Braumye\LoginVerification\Actions;

class LoginVerifyRoute
{
    public function make($code, $email): string
    {
        return route('login-verification.confirm', [
            'email' => $email,
            'token' => $this->makeToken($code, $email),
        ]);
    }

    protected function makeToken($code, $email): string
    {
        return encrypt([
            'code' => $code,
            'email' => $email,
            'timestamp' => time(),
        ]);
    }
}
