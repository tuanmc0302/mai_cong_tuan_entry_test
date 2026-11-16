<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $this->userRepository->get($request->user()->id),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->safe()->only(['name', 'email', 'gender']);

        // Change password upon entry
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload avatar, delete old file
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $this->userRepository->update($user->id, $data);

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Cập nhật thông tin thành công.');
    }
}