<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('user.profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateProfilePhoto(Request $request)
{
    $file_dir = 'images/uploads/';

    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'profilePicture' => 'required|image|mimes:png,jpg,jpeg|max:10240',
    ]);

    $user = User::findOrFail($request->user_id);

    // Check if file is uploaded
    if ($request->hasFile('profilePicture')) {
        $file = $request->file('profilePicture');

        // Create unique name to prevent overwriting
        $file_name = time() . '_' . $file->getClientOriginalName();

        // Move file to target directory (inside public)
        $file->move(public_path($file_dir), $file_name);

        // Update user's profile photo path
        $user->profile_photo_path = $file_dir . $file_name;
        $user->save();
    }

    return redirect()->back()->with('success', 'Profile photo updated successfully!');
}

}
