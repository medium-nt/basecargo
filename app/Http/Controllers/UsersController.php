<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function autologin(string $email)
    {
        if (! App::environment(['local'])) {
            abort(403, 'Доступ запрещён');
        }

        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            abort(404, 'Пользователь не найден');
        }

        Auth::login($user);

        return redirect('/admin');
    }
}
