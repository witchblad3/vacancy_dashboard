@extends('enterviews.index')
@section('title')
    <title>{{ $title }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection
@section('content')
<div>
    <form method="POST" action="{{route('auth')}}" class="form" style="padding: 50px;">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div id="container" class="container_own">
            <h1>Авторизация</h1>
            @if(count($errors) > 0)
                <div style="background: #f20c0c;border-radius: 15px;padding: 15px;margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        <p>{{$error}}</p>
                    @endforeach
                </div>
            @endif
            <div class="input-form">
                <input name="login" type="text" placeholder="Логин" required value="{{old('login')}}"/>
            </div>
            <div class="input-form">
                <input name="password" type="password" placeholder="Пароль" required />
                <a class="register_link" href="{{route('register')}}">Зарегистрироваться</a>
            </div>
            <button class="btn_submit" type="submit">Войти</button>
        </div>
    </form>
</div>
@endsection
