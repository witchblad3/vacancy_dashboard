<div class="modal fade" id="edit_modal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button onclick="closeEditModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <div id="modal_text">
                        <label for="header_vac">Название вакансии*</label>
                        <input required
                               type="text"
                               class="form-control"
                               id="header_vac"
                               name="name"/>
                    </div>
                    <div class="form-group">
                        <label for="salary">Зарплата*</label>
                        <input required
                               type="text"
                               class="form-control"
                               id="salary"
                               name="salary"
                               placeholder="35000-90000"/>
                    </div>
                    <div class="form-group">
                        <label for="company_name">Название компании*</label>
                        <input required
                               type="text"
                               class="form-control"
                               id="company_name"
                               name="company_name"
                               value="{{$creator->company_name}}"/>
                        @if($creator->company_name != null)
                            <input type="hidden" value="{{$creator->company_name}}" name="company_name">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="expirience">Требуемый опыт*</label><br/>
                        <select id="expirience"
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
                        <label for="description">Описание вакансии*</label>
                        <textarea required
                                  class="form-control"
                                  id="description"
                                  rows="3"
                                  name="description"></textarea>
                    </div>
                </div>

                <div id="edit_spinner" class="spinner-border" role="status" style="margin-left: 45%; margin-top: 50%; position: absolute;">
                    <span class="sr-only"></span>
                </div>
            <div class="modal-footer" id="modal_footer">
                <button onclick="save()" class="btn btn-primary" style="left: 10px; position: absolute;">Сохранить</button>
                <button id="close_btn" onclick="closeEditModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
            </div>

        </div>
    </div>
</div>

<script>
    var vacancyId;

    function getVacId(){
        return vacancyId;
    }

    function setVacancyId(value){
        vacancyId = value;
    }

    function openEditModal(id, event){
        $('#edit_modal').modal('show');
        $('#edit_spinner').show();
        setVacancyId(id)
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/get_vacancy/" + id;
        $.post(url, data, function (res){

            $('#header_vac').val(res.vacancy.name);
            $('#salary').val(res.vacancy.salary);
            $('#company_name').val(res.vacancy.company_name);
            $('#description').val(res.vacancy.description);
            let exp = $('#expirience')[0].childNodes;
            for(let i = 1; i < exp.length; i += 2){
                if(exp[i].value === res.vacancy.expirience){
                    exp[i].setAttribute('selected', 'selected');
                }
            }
        });
        $('#edit_spinner').hide();
    }
    function closeEditModal(){
        $('#edit_modal').modal('hide');
    }
    function save(){
        let id = getVacId();
        let data = getValuesFromModal();
        data._token = "{{csrf_token()}}";

        let errors = false;
        for (let elem in data){
            if (data[elem].trim() === ""){
                errors = true
            }
        }
        if(errors)
            alert("Нужно заполнить все поля");

        data.vacancyId = id;
        let url = "{{route('vacancy.update')}}";
        $.post(url, data, function (res){
            if(res.status === 200){
                closeEditModal();
                window.location.reload();
            }
        });
    }
    function getValuesFromModal(){
        let data = {};
        data.name = $('#header_vac').val();
        data.salary = $('#salary').val();
        data.company_name = $('#company_name').val();
        data.description = $('#description').val();
        data.expirience = $('#expirience').val();
        return data;
    }
</script>
