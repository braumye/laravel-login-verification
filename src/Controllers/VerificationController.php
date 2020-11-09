<?php

namespace Braumye\LoginVerification\Controllers;

use Braumye\LoginVerification\Exceptions\MissingVerification;
use Braumye\LoginVerification\Models\LoginVerification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class VerificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $verification = LoginVerification::createByEmail($request->email);
        $verification->send();

        return $this->renderResponse($verification);
    }

    public function status(Request $request)
    {
        $code = $request->input('code');
        $email = $request->input('email');
        if ($code && $email) {
            $verification = LoginVerification::query()->where([
                'code' => $code,
                'email' => $email,
            ])->first();

            if ($verification) {
                return $this->renderResponse($verification);
            }
        }

        throw new MissingVerification;
    }

    protected function renderResponse($verification)
    {
        return response()->json([
            'code' => $verification->code,
            'email' => $verification->email,
            'status' => $verification->status,
        ]);
    }

    public function confirm(Request $request)
    {
        if (! $token = $request->input('token')) {
            return $this->makeConfirmView(trans('login-verification.missing_token'));
        }

        if (! $email = $request->input('email')) {
            return $this->makeConfirmView(trans('login-verification.missing_email'));
        }

        try {
            $token = decrypt($token);
        } catch (Throwable $e) {
            return $this->makeConfirmView(trans('login-verification.invalid_token'));
        }

        if (isset($token['email']) === false) {
            return $this->makeConfirmView(trans('login-verification.invalid_token'));
        }

        if ($token['email'] !== $email) {
            return $this->makeConfirmView(trans('login-verification.invalid_email'));
        }

        if (isset($token['timestamp']) === false) {
            return $this->makeConfirmView(trans('login-verification.invalid_token'));
        }

        $expiresIn = config('login-verification.expires_in', 3600);
        if ($token['timestamp'] + $expiresIn < time()) {
            return $this->makeConfirmView(trans('login-verification.token_expired'));
        }

        $verification = LoginVerification::query()
            ->where('code', $token['code'])
            ->where('email', $token['email'])
            ->first();

        if (! $verification) {
            return $this->makeConfirmView(trans('login-verification.invalid_token'));
        }

        $verification->confirm();

        return $this->makeConfirmView(trans('login-verification.confirm_successfully'));
    }

    protected function makeConfirmView($message)
    {
        return view(config('login-verification.views.confirm', 'loginVerification::confirm'), compact('message'));
    }
}
