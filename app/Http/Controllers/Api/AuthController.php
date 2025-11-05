<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        // ✅ Bước 1: Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // cần thêm field password_confirmation bên frontend
        ]);

        try {
            // ✅ Bước 2: Tạo user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'user'
            ]);

            // ✅ Bước 3: Tạo token (nếu bạn có cài Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            // ✅ Bước 4: Trả response
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký thành công!',
                'data' => [
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể tạo tài khoản!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
     // Đăng nhập
   public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'Không tìm thấy người dùng'], 401);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Mật khẩu không chính xác'], 401);
    }

    // 🔹 Chuẩn hóa role
    $role = trim(strtolower($user->role));

    // 🔹 Phân biệt token
    $tokenName = $role === 'admin' ? 'admin_auth_token' : 'user_auth_token';
    $token = $user->createToken($tokenName)->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $role,
        ]
    ]);
}
}
