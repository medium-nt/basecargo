@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="col-md-6">
        <div class="card">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Роль</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $user->roleName }}"
                               readonly disabled>
                    </div>

                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="form-group">
                        <label for="messenger">Мессенджер</label>
                        <select name="messenger" id="messenger" class="form-control @error('messenger') is-invalid @enderror">
                            <option value="">---</option>
                            <option value="telegram" {{ old('messenger', $user->messenger) === 'telegram' ? 'selected' : '' }}>Telegram</option>
                            <option value="whatsapp" {{ old('messenger', $user->messenger) === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="wechat" {{ old('messenger', $user->messenger) === 'wechat' ? 'selected' : '' }}>WeChat</option>
                            <option value="viber" {{ old('messenger', $user->messenger) === 'viber' ? 'selected' : '' }}>Viber</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="messenger_number">Номер в мессенджере</label>
                        <input type="text"
                               class="form-control @error('messenger_number') is-invalid @enderror"
                               id="messenger_number"
                               name="messenger_number"
                               value="{{ old('messenger_number', $user->messenger_number) }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Новый пароль</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               value="{{ old('password') }}">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Подтверждение пароля</label>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               value="{{ old('password_confirmation') }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
