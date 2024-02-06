<!doctype html>
<head>
    <title>Добавить новость</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/headers.css">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/headers/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />-->
</head>
<body>
@include('layouts.header')
<div class="container">
    <div class="row">
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
        <h3>Добавить новость</h3>
        <form method="post" action="{{route('add_news_item')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="header_news">Заголовок*</label>
                <input required type="text" class="form-control" id="header_news" name="header_news"/>
            </div>

            <div class="form-group">
                <label for="text_news">Текст*</label>
                <textarea required class="form-control" id="text_news" rows="3" name="text_news"></textarea>
            </div>
            <div class="form-group">
                <label for="file_news">Картинка*</label>
                <input required type="file" class="form-control" id="file_news" accept="image/png, image/jpeg" name="file_news" />
            </div>
            <button style="margin-top: 10px;" type="submit" class="btn btn-primary">Опубликовать новость</button>
        </form>
    </div>
    <div class="row mt-5 mb-5">
        <h2>Мои новости ({{$count}})</h2>
        {{$news->links('paginator.paginate')}}
        @foreach($news as $article)
            <div class="card" style="width: 18rem; margin: 5px;" id="{{$article->id}}" onclick="openModal(id)">
                <img class="card-img-top" src="{{asset('/storage/'.$article->image_url)}}" alt="Card image cap" style="width: 260px; height: 150px;">
                <div class="card-body">
                    <h5 class="card-title" style="min-height: 48px;">{{$article->header}}</h5>
                    <div>
                        <p class="card-text" style="display: block;min-height: 96px;">{{$article->article_text}}</p>
                    </div>

                    <button id="{{$article->id}}" onclick="openModal(id)" class="btn btn-primary" style="bottom: 15px;">Прочитать</button>
                </div>
            </div>
        @endforeach
        {{$news->links('paginator.paginate')}}
    </div>
@include('layouts.footer')
</div>
@include('news.news_modal')
</body>
</html>
