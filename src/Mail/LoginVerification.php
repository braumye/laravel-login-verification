<?php

namespace Braumye\LoginVerification\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Verification code.
     *
     * @var string
     */
    public $code;

    /**
     * Verification url.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new message instance.
     *
     * @param  string  $code
     * @param  string  $url
     * @return void
     */
    public function __construct(string $code, string $url)
    {
        $this->code = $code;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view(config('login-verification.views.mail'));
    }
}
