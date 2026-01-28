<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Models\CargoShipment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when(auth()->user()->isManager(), function ($query) {
                $query->whereIn('role_id', [1, 2]);
            });

        return view('users.index', [
            'title' => 'Пользователи',
            'users' => $users->paginate(10),
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'title' => 'Добавить сотрудника',
        ]);
    }

    public function store(UsersRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::query()->create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь добавлен');
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'title' => 'Изменить пользователя',
            'user' => User::find($user->id),
        ]);
    }

    public function update(UsersRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        if (! $user->update($data)) {
            return back()->withErrors('Не удалось сохранить изменения.');
        }

        return back()->with('success', 'Изменения сохранены.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id) {
            return back()
                ->with('error', 'Нельзя удалить самого себя.');
        }

        $userHasAnyShipment = CargoShipment::query()
            ->where('responsible_user_id', $user->id)
            ->orWhere('client_id', $user->id)
            ->exists();

        if ($userHasAnyShipment) {
            return back()
                ->with('error', 'Пользователь участвует в отгрузках. Удаление невозможно.');
        }

        User::query()->findOrFail($user->id)->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь удален');
    }
}
