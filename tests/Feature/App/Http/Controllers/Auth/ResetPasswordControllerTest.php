<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\RequestFactories\SignUpFormRequestFactory;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    /**
     * @test
     * @return void
     */
    public function it_page_success(): void
    {
        $this->get(action([ResetPasswordController::class, 'page'], ['token' => $this->token]))
            ->assertOk()
            ->assertViewIs('auth.reset-password');
    }

    /**
     * @test
     * @return void
     */
    public function it_handle(): void
    {
        $password = '1234567890';
        $password_confirmation = '1234567890';

        Password::shouldReceive('reset')
            ->once()
            ->withSomeOfArgs([
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
                'token' => $this->token
            ])
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'token' => $this->token
        ]);

        $response->assertRedirect(action([SignInController::class, 'page']));
    }


}
