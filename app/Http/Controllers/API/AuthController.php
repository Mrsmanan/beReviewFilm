<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail;
use App\Mail\GenerateEmailMail;
use App\Models\OtpCode;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8|confirmed'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'min' => 'inputan :attribute minimal :min karakter',
            'email' => 'inputan :attribute harus berformat email',
            'unique' => 'inputan email sudah terdaftar',
            'confirmed' => 'inputan password beda dengan konfirmasi password'
        ]);

        $user = new User;

        $roleUser = Roles::where('name', 'user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegisterMail($user));

        return response([
            "message" => 'User berhasil Register, silahkan cek email anda',
            "user" => $user,
        ], 200);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'required' => 'inputan :attribute harus diisi',
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid'], 401);
        }

        $user = User::with(['profile', 'role'])->where('email', $request->input('email'))->first();
        return response([
            "message" => 'User berhasil login',
            "user" => $user,
            'token' => $token
        ], 200);
    }

    public function currentuser()
    {
        $user = auth()->user();
        return response()->json([
            "user" => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'email' => 'inputan harus berupa email'
        ]);

        $user = User::where('email', $request->input('email'))->first();
        $user->generate_otp();
        Mail::to($user->email)->send(new GenerateEmailMail($user));
        return response([
            "message" => 'OTP code berhasil generate, cek email anda'
        ], 200);
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'min' => 'inputan maksimal :min karakter'
        ]);

        $user = auth()->user();

        //otp kode notfound
        $otp_code = OtpCode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if (!$otp_code) {
            return response([
                "message" => "OTP tidak ditemukan"
            ], 400);
        }

        //valid until > waktu sekarang
        $now = Carbon::now();
        if ($now > $otp_code->valid_until) {
            return response([
                "message" => "OTP kadaluarsa, silahkan generate ulang"
            ], 400);
        }

        //update user
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;
        $user->save();
        $otp_code->delete();

        return response([
            "message" => "Verifikasi email berhasil"
        ], 200);
    }
}
