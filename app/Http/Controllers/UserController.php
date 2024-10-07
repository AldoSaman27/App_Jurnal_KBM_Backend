<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware("auth:sanctum", ["except" => ["login", "register"]]);
    }

    public function register(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            "nip" => "required|numeric|min:18|unique:users",
            "password" => "required|string|min:5",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid field",
                "errors" => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            "nip" => $request->nip,
            "password" => bcrypt($request->password),
        ]);
        $accessToken = $user->createToken("accessToken")->plainTextToken;

        return response()->json([
            "message" => "User register success",
            "user" => $user,
            "accessToken" => $accessToken,
        ]);
    }

    public function login(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            "nip" => "required|numeric|min:18",
            "password" => "required|string|min:5",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid field",
                "errors" => $validator->errors(),
            ], 422);
        }

        if (!Auth::attempt($request->only("nip", "password")))
            return response()->json(["message" => "NIP atau Kata sandi salah!"], 401);

        $user = User::where("nip", $request->nip)->first();
        $accessToken = $user->createToken("accessToken")->plainTextToken;

        return response()->json([
            "message" => "User login success",
            "user" => $user,
            "accessToken" => $accessToken,
        ]);
    }

    public function logout(Request $request, User $user) {
        $request->user()->currentAccessToken("accessToken")->delete();
        return response()->json(["message" => "User logout success"]);
    }

    public function update(Request $request, User $user, $nip) {
        $validator = Validator::make($request->all(), [
            "foto_profil" => "nullable|image",
            "nama" => "nullable|string",
            "mata_pelajaran" => "nullable|string",
            "sekolah" => "nullable|string",
        ]);

        if ($validator->fails()) return response()->json([
            "message" => "Invalid field",
            "errors" => $validator->errors(),
        ], 422);

        $user = User::where("nip", $nip)->first();
        if (!$user) return response()->json(["message" => "User (NIP: $nip) tidak di temukan!"], 404);

        if ($request->hasFile('foto_profil')) 
        {
            $file = $request->file('foto_profil');
            $extension = $file->getClientOriginalExtension();
            $fileName = date('Ymd') . '_' . uniqid() . '.' . $extension;
            $file->storeAs('profile-picture', $fileName);

            $user->update(["foto_profil" => $fileName]);
        }
        if ($request->nama) $user->update(["nama" => $request->nama]);
        if ($request->mata_pelajaran) $user->update(["mata_pelajaran" => $request->mata_pelajaran]);
        if ($request->sekolah) $user->update(["sekolah" => $request->sekolah]);

        return response()->json([
            "message" => "User update success",
            "user" => $user,
        ]);
    }
}
