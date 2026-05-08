<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\User::query();

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
    }

    $users = $query->latest()->paginate(10);

    return view('admin.users', compact('users'));
}
    /* Update the role of a user. */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:employee,hype,admin'
        ]);

        // Prevent demoting yourself
        if(auth()->id() === $user->id) {
            return back()->with('error', 'You cannot modify your own role.');
        }

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'User role updated successfully.');
    }
}