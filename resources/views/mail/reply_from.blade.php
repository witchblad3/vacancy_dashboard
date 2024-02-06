@extends('mail.index')
@section('includes')
    <title>Ответить на сообщение</title>
    <style>
        .mail-info{
            background-color: #b6b6b6;
            border-radius: 5px;
            margin: 0;
            border: 6px solid #b6b6b6;
            display: grid;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="width: 80%; min-height: 500px;">
        <h3>Ответить на сообщение</h3>
        <form action="{{route('send_mail')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="recipient">Кому</label>
                <input type="text"
                       class="form-control"
                       name="recipient"
                       id="recipient" value="{{$recipient->first_name}} {{$recipient->last_name}}" disabled>
                <input type="hidden" value="{{$recipient->id}}" name="recipient_id" />
            </div>
            <div class="form-group">
                <label for="subject">Тема</label>
                <input required type="text"
                       class="form-control"
                       id="subj"
                       placeholder="тема" value="{{$mail->subject}}" disabled>
                <input name="subject" id="hidden_subject" type="hidden" value="{{$mail->subject}}" />
                <input name="vacancy_id" id="hidden_field" type="hidden" value="{{$mail->id}}" />
                <input name="is_reply" id="hidden_field" type="hidden" value="1" />
            </div>
            <div class="form-group">
                <label for="text">Текст письма</label>
                <textarea required name="text" class="form-control" id="text" rows="3"></textarea>
            </div>
            <div style="margin-top: 15px;">
                <button onclick="submitValues()" type="submit" class="btn btn-primary">Отправить</button>
            </div>

        </form>

        <h4>Ответ на сообщение: </h4>
        <div class="mail-info" style="margin-top: 15px">
            <span id="from">От: {{$recipient->first_name}} {{$recipient->last_name}}</span>
            @if(isset($mail['recipientObject']->company_name))
                <span id="to">Кому: {{$mail['recipientObject']->company_name}}</span>
            @else
                <span id="to">Кому: {{$mail['recipientObject']->first_name}} {{$mail['recipientObject']->last_name}}</span>
            @endif
        </div>
        <div>
            {{$mail->mail_text}}
        </div>
    </div>
@endsection
@section('scripts')
@endsection
