<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Logic to create a new user
        $user = User::create($fields);
        $token = $user->createToken($request->name);
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $fields = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if (isset($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        }

        $user->update($fields);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);






    }








    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function login (Request $request)
    {
          $validated = $request->validate([
        'current_password' => [
            'required',
            'string',
            function ($attribute, $value, $fail) use ($request) {
                if (!Hash::check($value, $request->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }
        ],
        'new_password' => [
            'required',
            'string',
            'confirmed',
            'different:current_password',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
        ],
    ]);

    $request->user()->update([
        'password' => Hash::make($validated['new_password'])
    ]);

    return response()->json(['message' => 'Password changed successfully']);
}
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if (!Hash::check($validated['current_password'], $request->user()->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 403);
        }

        $request->user()->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }
}







