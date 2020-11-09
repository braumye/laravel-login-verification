<?php

namespace Braumye\LoginVerification\Exceptions;

use Exception;

class MissingVerification extends Exception
{
    public function __construct()
    {
        parent::__construct(trans('login-verification.missing_token'));
    }
}
