@extends('mail.index')
@section('includes')
    <title>Мои сообщения</title>
@endsection
@section('content')
    <div class="container" style="width: 80%; min-height: 500px;">
        <a style="margin-bottom: 15px; margin-right: 15px;" class="btn btn-primary" href="{{route('sent_mail')}}">Отправленные</a>
        <a style="margin-bottom: 15px;" class="btn btn-primary" href="{{route('unread_mail')}}">Непрочитанные</a>
        {{$mail->links('paginator.paginate')}}
        @foreach($mail as $letter)
            <div class="card" style="margin-top:10px;" id="{{$letter->id}}" onclick="openMailModal(id)">
                <h5 style="display: flex;justify-content: space-between;" class="card-header">От {{$letter['sender']->first_name}} {{$letter['sender']->last_name}}

                    <span>{{beautyDate($letter->created_at)}}</span></h5>
                <div class="card-body">
                    <h5 class="card-title">{{$letter->subject}}</h5>
                    <p class="card-text">{{$letter->mail_text}}</p>
                    @if(is_null($letter->reply_to) == false)
                        <div style="background-color: #b6b6b6; border-radius: 5px; margin: 0 0 15px 0; border: 6px solid #b6b6b6; display: grid;">
                            <span id="big-message-from">Ответ на ваше:</span>
                            <span id="big-message-to">{{$letter['repliedLetter']->mail_text}}</span>
                        </div>
                    @endif
                    <a href="#" class="btn btn-primary">Прочитать</a>
                </div>
                @if($letter->is_read == false)
                    <span class="badge badge-pill badge-primary"
                          id="{{$letter->id}}_new"
                          style="background-color: #0d6efd;">Новое</span>
                @endif
            </div>
        @endforeach
        {{$mail->links('paginator.paginate')}}
        @if(count($mail) == 0)
            <div style="border-radius: 15px;padding: 15px;margin-bottom: 15px;text-align: center;">
                Нет входящих писем.
            </div>
        @endif
    </div>
    @include('mail.mail_modal')
@endsection
@section('scripts')
@endsection
