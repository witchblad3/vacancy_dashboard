@extends('vacancies.index')

@section('includes')
<title>Каталог вакансий</title>
@endsection

@section('content')
    <div style="width: 80%; min-height: 500px;" class="container">
        <h1 style="margin-left: 25%;">Каталог вакансий</h1>
        <div style="margin-left: 25%;">
        {{$vacancies->links('paginator.paginate')}}
        </div>
        @forelse($vacancies as $vac)
            <div class="row row-cols-1 row-cols-md-2 g-4" style="margin-bottom: 15px; justify-content: center;">
                <div class="col">
                    <div class="card" onclick="openModal({{$vac->id}}, event)">
                        <div class="card-body">
                            <p class="text-small" style="margin: 0;text-align: right;">{{$vac->news_created}}</p>
                            <h5 class="card-title">{{$vac->name}}</h5>
                            <p class="card-title">{{$vac->company_name}}</p>
                            <p class="card-title">{{$vac->salary}} рублей</p>
                            <p class="card-title">требуемый опыт: {{$vac->expirience}}</p>
                            <p class="card-text">{{$vac->description}}</p>
                            <button id="{{$vac->id}}" class="btn btn-primary">Прочитать</button>
                            @if(isFavoriteVacancie($vac->id, $favs))
                                <button id="favorited"
                                        onclick="makeUnfavorite({{$vac->id}})"
                                        type="button"
                                        class="btn btn-warning">В избранном</button>
                            @else
                            <button id="fav"
                                    onclick="makeFavorite({{$vac->id}})"
                                    class="btn btn-outline-secondary">
                                В избранное</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="row row-cols-1 row-cols-md-2 g-4" style="margin-bottom: 15px; justify-content: center;">
                <div class="col">
                    <div class="card" style="height: 430px; text-align: center; border: none;">
                        <h1 style="top: 50%; left: 25%; margin: 0; position: absolute;">Здесь пока нет вакансий.</h1>
                    </div>
                </div>
            </div>
        @endforelse
        <div style="margin-left: 25%;">
            {{$vacancies->links('paginator.paginate')}}
        </div>
    </div>
    @include('vacancies.vacancy_modal')
@endsection

@section('scripts')
<script>
    function makeFavorite(id){
        let data = {
            _token: "{{csrf_token()}}",
            id: id
        };
        let url = "/make_fav";
        $.post(url, data, function (res){
            window.location.reload();
        });
    }
    function makeUnfavorite(id){
        let data = {
            _token: "{{csrf_token()}}",
            id: id
        };
        let url = "/make_unfav";
        $.post(url, data, function (res){
            window.location.reload();
        });
    }
</script>
@endsection
