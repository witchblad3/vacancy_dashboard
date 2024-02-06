<!doctype html>
<html>
<head>
    <link type="Image/x-icon" href="/favicon.png" rel="icon">
    <title>Новости</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!--<link rel="stylesheet" href="/css/style.css">-->
    <link rel="stylesheet" href="/css/headers.css">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/headers/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body>
@include('layouts.header')
<div class="container">
    <div style="/*position: relative;top: 50%;left: 25%;*/">
        <h1 style="margin-left: 25%;">Новости</h1>
        @if(count($news) > 0)
            <div style="margin-left: 25%;">
                {{$news->links('paginator.paginate')}}
            </div>
        @foreach($news as $article)
            <div class="row row-cols-1 row-cols-md-2 g-4" style="margin-bottom: 15px; justify-content: center;">
                <div class="col">
                    <div class="card" id="{{$article->id}}" onclick="openModal(id)">
                        <img width="100%" height="320" src="{{asset('/storage/'.$article->image_url)}}" class="bd-placeholder-img card-img-top">
                        <div class="card-body">
                            <p class="text-small" style="margin: 0;text-align: right;">{{$article->news_created}}</p>
                            <h5 class="card-title">{{$article->header}}</h5>
                            <p class="card-text">{{$article->article_text}}</p>
                            <button id="{{$article->id}}" onclick="openModal(id)" class="btn btn-primary">Прочитать</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
            <div style="margin-left: 25%;">
                {{$news->links('paginator.paginate')}}
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4" style="margin-bottom: 15px; justify-content: center;">
                <div class="col">
                    <div class="card" style="height: 430px; text-align: center; border: none;">
                        <h1 style="top: 50%; left: 25%; margin: 0; position: absolute;">Новостей пока нет.</h1>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @include('layouts.footer')
</div>

@include('news.news_modal')
</body>
</html>
