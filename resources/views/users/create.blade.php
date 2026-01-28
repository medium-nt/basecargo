@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="role_id">Роль</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option value="" disabled selected>---</option>
                                    <option value="1"
                                            @if(old('role_id') == 1) selected @endif>
                                        Клиент
                                    </option>
                                    <option value="2"
                                            @if(old('role_id') == 2) selected @endif>
                                        Агент
                                    </option>
                                    @if(auth()->user()->isAdmin())
                                    <option value="4"
                                            @if(old('role_id') == 4) selected @endif>
                                        Менеджер
                                    </option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       placeholder=""
                                       value="{{ old('name') }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       placeholder=""
                                       value="{{ old('email') }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       placeholder=""
                                       value="{{ old('phone') }}">
                            </div>

                            <div class="form-group">
                                <label for="messenger">Мессенджер</label>
                                <select name="messenger" id="messenger" class="form-control @error('messenger') is-invalid @enderror">
                                    <option value="">---</option>
                                    <option value="telegram" {{ old('messenger') === 'telegram' ? 'selected' : '' }}>Telegram</option>
                                    <option value="whatsapp" {{ old('messenger') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="wechat" {{ old('messenger') === 'wechat' ? 'selected' : '' }}>WeChat</option>
                                    <option value="viber" {{ old('messenger') === 'viber' ? 'selected' : '' }}>Viber</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="messenger_number">Номер в мессенджере</label>
                                <input type="text"
                                       class="form-control @error('messenger_number') is-invalid @enderror"
                                       id="messenger_number"
                                       name="messenger_number"
                                       placeholder=""
                                       value="{{ old('messenger_number') }}">
                            </div>

                            <div class="form-group">
                                <label for="password">Пароль</label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder=""
                                       value="{{ old('password') }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Подтверждение пароля</label>
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder=""
                                       value="{{ old('password_confirmation') }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@endsection

