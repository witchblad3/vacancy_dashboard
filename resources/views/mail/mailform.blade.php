@extends('mail.index')
@section('includes')
    <title>Отправить сообщение</title>
@endsection
@section('content')
    <div class="container" style="width: 80%; min-height: 500px;">
        <h3>Отправить сообщение</h3>
        <form action="{{route('send_mail')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="recipient">Кому</label>
                <input type="text"
                       class="form-control"
                       name="recipient"
                       id="recipient" value="{{$company->company_name}}" disabled>
                <input type="hidden" value="{{$company->id}}" name="recipient_id" />
            </div>
            <div class="form-group">
                <label for="subject">Тема</label>
                <input required type="text"
                       class="form-control"
                       id="subj"
                       placeholder="тема" value="{{$vac->name}}" disabled>
                <input name="subject" id="hidden_subject" type="hidden" value="{{$vac->id}}" />
                <input name="vacancy_id" id="hidden_field" type="hidden" value="{{$vac->id}}" />
            </div>
            <div class="form-group">
                <label for="text">Текст письма</label>
                <textarea required name="text" class="form-control" id="text" rows="3"></textarea>
            </div>
            <div style="margin-top: 15px;">
                <button onclick="submitValues()" type="submit" class="btn btn-primary">Отправить</button>
            </div>

        </form>
    </div>
@endsection
@section('scripts')
    <script>
        function submitValues(){
            $('#hidden_subject').val($('#subj').val());
        }
    </script>
@endsection
