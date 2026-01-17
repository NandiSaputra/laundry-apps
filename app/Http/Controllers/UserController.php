<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create-user');
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        return view('admin.user.edit-user', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->userService->updateUser($id, $request->validated());
        return redirect()->route('admin.user')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus!');
    }
}
