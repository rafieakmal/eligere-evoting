<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function registration(Request $request) {
        $request->validate([
            'email' => 'required|string|email|max:225',
            'password' => 'required|string|confirmed|min:6',
            'username' => 'required',
            'phone' => 'required',
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'role' => 'user',
            'image' => '/assets/img/user/'.$request->username,
            'phone' => $request->phone,
            'is_verified' => '1',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registration Successful.'], 201);
    }

    public function checkUsername($username, $email) {
        $userExists = User::where(function ($query) use ($username, $email) {
            $query->where('email', '=', $email)
                ->orWhere('username', '=', $username);
        })->exists();
        return response()->json($userExists);
    }

    public function login(Request $request) {
        $credentialsEmail = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        if (Auth::attempt($credentialsEmail)) {
            $status = 200;
            $user = Auth::user();
            $response = [
                'user' =>  $user,
                'token' => JWTAuth::fromUser($user),
            ];
        } else {
            $status = 422;
            $response = ['error' => 'The email or password is incorrect.'];
        }

        return response()->json($response, $status);
    }

    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png',
            'name' => 'required',
        ]);
        $file_name = $request->name.'.jpg';
        $file_path = $request->file('file')->move(public_path('assets/img/user'), $file_name);
        return response()->json($file_path);
    }

    public function code(Request $request) {
        $response = Http::get('http://127.0.0.1:3000/api/wa/get-code');
        return $response->json();
    }

    public function sendCode(Request $request) {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string'
        ]);

        $response = Http::asForm()->post('http://127.0.0.1:3000/api/wa/send-code',[
                'number' => $request->number,
                'message' => $request->message
            ]
        );
        return $response->json();

    }

    public function notification(Request $request) {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string'
        ]);

        $response = Http::asForm()->post('http://127.0.0.1:3000/api/wa/send-message',[
                'number' => $request->number,
                'message' => $request->message
            ]
        );
        return $response->json();

    }

    public function ktp(Request $request) {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png',
        ]);
        $image = $request->file('file');
        $res = Http::attach('images', file_get_contents($image), 'image.jpg')
            ->post('http://127.0.0.1:5000/api/ktp/verify-ktp', $request->all());
        return $res->json();

    }

    public function nik(Request $request) {
        $request->validate([
            'nik' => 'required|string',
        ]);
        $response = Http::asForm()->post('http://127.0.0.1:5000/api/ktp/verify-nik',[
            'nik' => $request->nik
        ]
        );
        return $response->json();

    }

    public function checkPhone(Request $request) {
        $request->validate([
            'number' => 'required|string',
        ]);
        $userExists = User::where([
            ['phone', '=', $request->number]
        ])->exists();
        return response()->json($userExists);

    }

    public function sendPasswordResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'));
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent to your email.']);
        }

        return response()->json(['error' => 'Failed to send reset link.'], 400);
    }

    protected function broker() {
        return Password::broker();
    }

    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset.']);
        } else {
            return response()->json(['error' => 'Failed to reset password, because your link is expired or error'], 400);
        }
    }
}
