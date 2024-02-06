@extends('vacancies.index')

@section('includes')
    <title>Создать вакансию</title>
@endsection

@section('content')
<div style="width: 80%; min-height: 500px;" class="container">
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
        <h3>{{$formTitle}}</h3>
        <form action="{{route('add_vacancy_item')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="header_news">Название вакансии*</label>
                <input required
                       type="text"
                       class="form-control"
                       id="header_news"
                       name="name"/>
            </div>
            <div class="form-group">
                <label for="header_news">Зарплата*</label>
                <input required
                       type="text"
                       class="form-control"
                       id="header_news"
                       name="salary"
                       placeholder="35000-90000"/>
            </div>
            <div class="form-group">
                <label for="header_news">Название компании*</label>
                <input required
                       type="text"
                       class="form-control"
                       id="header_news"
                       name="company_name"
                       @if($creator->company_name != null) value="{{$creator->company_name}}" disabled @endif/>
                @if($creator->company_name != null)
                    <input type="hidden" value="{{$creator->company_name}}" name="company_name">
                @endif
            </div>

            <div class="form-group">
                <label for="select_picker">Требуемый опыт*</label><br/>
                <select id="select_picker"
                        name="expirience"
                        class="selectpicker form-control" >
                    <option value="0"></option>
                    <option value="до 1 года">до 1 года</option>
                    <option value="от 1 до 3 лет">от 1 до 3 лет</option>
                    <option value="от 3 до 5 лет">от 3 до 5 лет</option>
                    <option value="от 5 до 10 лет">от 5 до 10 лет</option>
                    <option value="10 лет и более">10 лет и более</option>
                </select>
            </div>

            <div class="form-group">
                <label for="text_news">Описание вакансии*</label>
                <textarea required
                          class="form-control"
                          id="text_news"
                          rows="3"
                          name="description"></textarea>
            </div>
            <button style="margin-top: 10px;" type="submit" class="btn btn-primary">Создать вакансию</button>
        </form>
    </div>
    <div class="row mt-5 mb-5">
        <h2>Мои вакансии ({{$vacCount}})</h2>
        {{$vacs->links('paginator.paginate')}}
        @foreach($vacs as $vac)
            <div class="card" style="width: 18rem; margin: 5px;">
                <div class="card-body">
                    <h5 class="card-title" style="min-height: 20px;">{{$vac->name}}</h5>
                    <p class="small-text disabled">{{$vac->company_name}}</p>
                    <p class="small-text disabled">требуемый опыт: {{$vac->expirience}}</p>
                    <p class="card-text" style="display: block;min-height: 96px;">{{$vac->salary}} рублей</p>
                    @if($vac->deleted_at)
                        <div
                            style="position: absolute;left: 0; top: 55%; width: 100%; text-align: center; background: red;">
                            Вакансия удалена</div>
                    @else
                    <button onclick="openModal({{$vac->id}}, event)" class="btn btn-success btn-sm" style="bottom: 15px;">Read</button>
                    <button onclick="openEditModal({{$vac->id}})" class="btn btn-primary btn-sm" style="bottom: 15px;">Edit</button>
                    <button onclick="deleteVacancy({{$vac->id}})" class="btn btn-danger btn-sm" style="bottom: 15px;">Delete</button>
                    @endif
                </div>
            </div>
        @endforeach
        {{$vacs->links('paginator.paginate')}}
    </div>
</div>
    @include('vacancies.vacancy_modal')
    @include('vacancies.edit_vacancy_modal')
@endsection

@section('scripts')
    <script>
        function deleteVacancy(id){
            let conf = confirm('Удалить вакансию?');
            if(conf === false){
                return;
            }

            let data = {
                _token: "{{csrf_token()}}",
                id: id
            }
            let url = "/vacancies/delete/" + id;
            $.post(url, data, function (res){
                window.location.reload();
            });
        }
    </script>
@endsection
