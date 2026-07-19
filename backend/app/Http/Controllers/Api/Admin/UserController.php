<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('articles')->orderBy('name')->get();
        return response()->json($users);
    }
    
    public function show(User $user)
    {
        $user->loadCount('articles');
        return response()->json($user);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,author',
            'bio' => 'nullable|string',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
        ]);
        
        return response()->json($user, 201);
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:admin,author',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
            'facebook_url' => 'nullable|string|max:500',
            'twitter_url' => 'nullable|string|max:500',
        ]);
        
        if ($request->has('name')) $user->name = $validated['name'];
        if ($request->has('email')) $user->email = $validated['email'];
        if ($request->has('password')) $user->password = Hash::make($validated['password']);
        if ($request->has('role')) $user->role = $validated['role'];
        if ($request->has('bio')) $user->bio = $validated['bio'];
        if ($request->has('avatar')) $user->avatar = $validated['avatar'];
        if ($request->has('facebook_url')) $user->facebook_url = $validated['facebook_url'];
        if ($request->has('twitter_url')) $user->twitter_url = $validated['twitter_url'];
        
        $user->save();
        
        return response()->json($user);
    }
    
    public function destroy(User $user)
    {
        if ($user->id === request()->user()->id) {
            return response()->json(['message' => 'Cannot delete yourself'], 409);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
