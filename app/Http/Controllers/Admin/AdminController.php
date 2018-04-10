<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthAdmin;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository )
    {
        $this->middleware(AuthAdmin::class);
        $this->userRepository = $userRepository;
    }

    public function showUserManagerment()
    {
        $list_users = $this->userRepository->all();
        foreach ($list_users as $key => $user) {
            $list_users[$key]->client_apps = $this->userRepository->getClientAppsByUserId($user->id);
        }

        return view('admins.user_managerment', ['list_users' => $list_users]);
    }

    public function removeUser(Request $request, $user_id)
    {
        $this->userRepository->removeUser($user_id);

        return redirect()->route('user-managerment');
    }
}
