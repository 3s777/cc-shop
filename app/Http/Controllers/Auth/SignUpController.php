<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class SignUpController extends Controller
{
    public function page(): Factory|View|Application
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {

//        $dto = new NewUserDTO(
//            $request->get('name'),
//            $request->get('email'),
//            $request->get('password')
//        );
//
//        $action($dto);


//        $action(NewUserDTO::fromRequest());
        // TODO make DTOs
//        $action(
//            $request->get('name'),
//            $request->get('email'),
//            $request->get('password')
//        );

        $action(NewUserDTO::fromRequest($request));

        return redirect()->intended(route('home'));
    }
}
