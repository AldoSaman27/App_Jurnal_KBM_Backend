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
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "nip" => $user->nip,
                "mapel" => $user->mapel,
                "foto" => $user->foto,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
                "accessToken" => $accessToken,
            ],
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
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "nip" => $user->nip,
                "mapel" => $user->mapel,
                "foto" => $user->foto,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
                "accessToken" => $accessToken,
            ],
        ]);
    }

    public function logout(Request $request, User $user) {
        $request->user()->currentAccessToken("accessToken")->delete();
        return response()->json(["message" => "User logout success"]);
    }

    public function update(Request $request, User $user, $id) {
        $validator = Validator::make($request->all(), [
            "foto" => "nullable|image",
            "name" => "required|string",
            "nip" => "required|string",
            "mapel" => "required|string",
            "email" => "required|string|email",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid field",
                "errors" => $validator->errors(),
            ], 422);
        }

        $user = User::where("id", $id)->first();
        if (!$user) return response()->json(["message" => "User (id: $id) tidak di temukan!"], 404);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            @$fileName = date('Ymd') . '_' . uniqid() . '.' . $extension;
            $file->move(public_path('uploads/foto'), $fileName);
            $updateUser = $user->update([
                "foto" => $fileName,
            ]);
        }

        $updateUser = $user->update([
            "name" => $request->name,
            "nip" => $request->nip,
            "mapel" => $request->mapel,
            "email" => $request->email,
        ]);

        if ($updateUser) {
            return response()->json([
                "message" => "User update success",
                "user" => $user,
            ]);
        }
    }

    public function checkToken() {
        return response()->json(["isValid" => true]);
    }
}
