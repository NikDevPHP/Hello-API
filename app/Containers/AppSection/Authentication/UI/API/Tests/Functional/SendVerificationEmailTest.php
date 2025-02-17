<?php

namespace App\Containers\AppSection\Authentication\UI\API\Tests\Functional;

use App\Containers\AppSection\Authentication\Notifications\VerifyEmail;
use App\Containers\AppSection\Authentication\UI\API\Tests\ApiTestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * @group authentication
 * @group api
 */
class SendVerificationEmailTest extends ApiTestCase
{
    protected string $endpoint = 'post@v1/email/verification-notification';

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function testGivenEmailVerificationEnabledSendVerificationEmail(): void
    {
        if (!config('appSection-authentication.require_email_verification')) {
            $this->markTestSkipped();
        }
        Notification::fake();
        $this->testingUser = UserFactory::new()->unverified()->createOne();

        $data = [
            'verification_url' => config('appSection-authentication.allowed-verify-email-urls')[0],
        ];

        $response = $this->makeCall($data);

        $response->assertAccepted();
        Notification::assertSentTo($this->testingUser, VerifyEmail::class);
    }

    public function testSendingWithoutRequiredDataShouldThrowError(): void
    {
        if (!config('appSection-authentication.require_email_verification')) {
            $this->markTestSkipped();
        }
        $data = [];

        $response = $this->makeCall($data);

        $response->assertUnprocessable();

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('errors')
                ->has(
                    'errors',
                    fn (AssertableJson $json) => $json->where('verification_url.0', 'The verification url field is required.'),
                ),
        );
    }

    public function testRegisterNewUserWithNotAllowedVerificationUrl(): void
    {
        if (!config('appSection-authentication.require_email_verification')) {
            $this->markTestSkipped();
        }
        $data = [
            'email' => 'test@test.test',
            'password' => 's3cr3tPa$$',
            'name' => 'Bruce Lee',
            'verification_url' => 'http://notallowed.test/wrong',
        ];

        $response = $this->makeCall($data);

        $response->assertUnprocessable();
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('errors')
                ->where('errors.verification_url.0', 'The selected verification url is invalid.'),
        );
    }

    public function testGivenEmailVerificationIsDisabledShouldThrow404(): void
    {
        if (config('appSection-authentication.require_email_verification')) {
            $this->markTestSkipped();
        }
        $response = $this->makeCall([]);

        $response->assertNotFound();
    }
}
