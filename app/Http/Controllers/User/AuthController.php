<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Str;

class AuthController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:user', ['except' => ['login', 'register', 'handleGoogleRedirect', 'handleGoogleCallback', 'loginWithGoogleId']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        $credentials['is_block'] = 0;
        $ttl =  request('remember_me') ? 24 * 60 * 10 : 10 * 60;
        if (!$token = auth('user')->setTTL($ttl)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(RegisterUserRequest $request)
    {
        $user_data = $request->validated();
        $user_data['password'] = Hash::make($user_data['password']);
        $user = User::create($user_data);
        $token = auth('user')->setTTL(24 * 60 * 3)->login($user);
        event(new Registered($user));
        return $this->createNewToken($token);
    }

    public function verifyUserEmail(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect('/?emailAlreadyVerified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return redirect('/?emailVerified');
    }

    public function sendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }

    public function handleGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $google_user = Socialite::driver('google')->user()->user;
        // setting this session variable for loginWithGoogleId() to work only in less than 90 seconds
        session()->put('google_auth_time', time());
        session()->put('session_google_id', $google_user['id']);
        // if user already exists with that email, update google id for that user (if not present) and also verify their email (if not yet verified). then redirect for login
        $user = User::where('email', $google_user['email'])->first();
        if ($user) {
            if (!$user->google_id) {
                $user->update(['google_id' => $google_user['id']]);
            }
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
            // redirecting to this route, will hit the loginWithGoogleId() function
            return redirect('/?googleAuth=' . $user->google_id);
        }
        // else create a new user
        else {
            // change low quality image to hight quality. then upload to our public storage.
            $image_hq = str_replace("s96-c", "s384-c", $google_user['picture']);
            $contents = file_get_contents($image_hq);
            $image = (string) Str::uuid() . '.jpg';
            Storage::disk('public')->put('/user/profiles/' . $image, $contents);
            $user_data = [
                'google_id' => $google_user['id'],
                'fname' => $google_user['given_name'],
                'lname' => $google_user['family_name'],
                'email' => $google_user['email'],
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($google_user['id']),
                'image' => $image
            ];
            $user = User::create($user_data);
            // redirecting to this route, will hit the loginWithGoogleId() function
            return redirect('/?googleAuth=' . $user->google_id);
        }
    }

    public function loginWithGoogleId()
    {
        //only login if the token was recieved in previous 90 seconds and make sure it is the same google id
        $google_auth_time = session()->get('google_auth_time');
        $session_google_id = session()->get('session_google_id');
        $google_id = request('google_id');
        if (time() - $google_auth_time < 90 && $google_id == $session_google_id) {
            $user = User::where('google_id', $google_id)->first();
            if ($token = auth('user')->setTTL(24 * 60 * 3)->login($user)) {
                return $this->createNewToken($token);
            }
        }
        return response()->json(['error' => 'Token Expired'], 401);
    }

    public function handleFacebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        // DOESN'T WORK FOR NOW
    }

    public function logout()
    {
        auth('user')->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('user')->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth('user')->user());
    }

    protected function createNewToken($token)
    {
        $user = auth('user')->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user')->factory()->getTTL() * 600,
            'user' => $user
        ]);
    }

    public function test()
    {
        dd(auth('user')->user());
        dd('asdfasdf');
    }
}
