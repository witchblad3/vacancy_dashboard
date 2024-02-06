@extends('enterviews.index')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('content')
    <div>
        <form method="POST" action="{{route('reg')}}" class="form">
            <div id="container" class="container_own">
                <h1>Регистрация</h1>
                @if(count($errors) > 0)
                <div style="background: #f20c0c;border-radius: 15px;padding: 15px;margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        <p>{{$error}}</p>
                    @endforeach
                </div>
                @endif
                @if(session()->has('success'))
                    <div style="background: rgba(37,229,8,0.4);border-radius: 15px;padding: 15px;margin-bottom: 15px;">
                        {{session()->get('success')}}
                    </div>
                @endif
                <div class="input-form">
                    <input name="login" type="text" placeholder="Логин" value="{{old('login')}}" />
                </div>
                <div class="input-form">
                    <input name="email" type="email" placeholder="Email" value="{{old('email')}}"/>
                </div>
                <div class="input-form">
                    <input name="first_name" type="text" placeholder="Имя" value="{{old('first_name')}}"/>
                </div>
                <div class="input-form">
                    <input name="last_name" type="text" placeholder="Фамилия" value="{{old('last_name')}}"/>
                </div>
                <div class="input-form">
                    <input type="hidden" name="jobgiver" value="0" />
                    <input type="checkbox" name="jobgiver" value="1" />
                    <p>Я работодатель</p>
                </div>
                <div class="input-form">
                    <input name="bdate" type="date" placeholder="Дата рождения" value="{{old('bdate')}}"/>
                </div>
                <div class="input-form">
                    <input name="exp" type="text" placeholder="Опыт работы" value="{{old('exp')}}"/>
                </div>
                <div class="input-form">
                    <input name="password" type="password" placeholder="Пароль" />
                </div>
                <div class="input-form">
                    <input name="confpass" type="password" placeholder="Повторите пароль" />
                    <a class="register_link" href="{{route('login')}}">Авторизоваться</a>
                </div>
                <button class="btn_submit" type="submit">Регистрация</button>
            </div>
            @csrf

        </form>
    </div>
@endsection
