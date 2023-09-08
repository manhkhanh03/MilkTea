<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200, ['OK']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['password'] = Hash::make($request->password);
        $check_user = User::where('login_name', $request->login_name)->first();
        $check_email = User::where('email', $request->email)->first();

        if ($check_user)
            return response()->json(['status' => 'username'], 200, ['!OK']);
        else if ($check_email)
            return response()->json(['status' => 'email'], 200, ['!OK']);

        $user =  User::create($request->all());
        if ($user)
            return response()->json($user, 200, ['OK']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user, 200, ['OK']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);
            $user->update($request->all());
            return response()->json($user, 200, ['OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update_password(Request $request, string $id)
    {
        $user = User::find($id);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error'=> 'Current password is incorrect.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json($user, 200, ['OK']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkuser() {
        if (Auth::guard('api')->check()) {
            return response()->json(Auth::guard('api')->user(), 200);;
        }else {
            return response()->json(['status' => 'false'], 200);
        }
    }
}