<?php

namespace Braumye\LoginVerification\Actions;

use Illuminate\Support\Str;

class LoginVerifyCode
{
    public function make(string $email): string
    {
        return Str::random(13);
    }
}
