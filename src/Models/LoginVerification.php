<?php

namespace Braumye\LoginVerification\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Braumye\LoginVerification\Actions\LoginVerifyCode;
use Braumye\LoginVerification\Actions\LoginVerifyRoute;
use Braumye\LoginVerification\Mail\LoginVerification as MailLoginVerification;

class LoginVerification extends Model
{
    public const STATUS_WAITING = 0;
    public const STATUS_CONFIRMED = 1;

    protected $fillable = [
        'code', 'email', 'status',
    ];

    public function getTable()
    {
        return config('login_verification.database.table_name', parent::getTable());
    }

    public static function createByEmail(string $email)
    {
        return static::query()->create([
            'code' => app(LoginVerifyCode::class)->make($email),
            'email' => $email,
            'status' => static::STATUS_WAITING,
        ]);
    }

    public function confirm()
    {
        $this->update(['status' => static::STATUS_CONFIRMED]);
    }

    public function send()
    {
        Mail::to($this->email)->send(new MailLoginVerification($this->code, $this->url()));
    }

    public function url(): string
    {
        return app(LoginVerifyRoute::class)->make($this->code, $this->email);
    }
}
