<?php

namespace Braumye\LoginVerification\Tests;

use Braumye\LoginVerification\Models\LoginVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class SendLoginVerificationMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_send_mail_of_login_verification_by_email()
    {
        Mail::fake();

        $response = $this->post('/send', [
            'email' => $email = 'foo@example.com',
        ])->assertSuccessful();

        $verification = LoginVerification::where('email', $email)->first();
        $this->assertNotNull($verification);

        $response->assertJsonFragment(['code' => $verification->code]);
    }
}
