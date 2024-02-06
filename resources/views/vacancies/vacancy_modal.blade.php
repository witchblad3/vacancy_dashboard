<div class="modal fade" id="jq_modal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button onclick="closeModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <div id="modal_text">

                </div>

                <div id="spinner" class="spinner-border" role="status" style="margin-left: 45%;">
                    <span class="sr-only"></span>
                </div>
            </div>
            <div class="modal-footer" id="modal_footer">
                <button id="close_btn" onclick="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id, event){
        if(event.target.id === "fav" || event.target.id === "favorited"){
            return;
        }
        $('#jq_modal').modal('show');
        $('#spinner').show();
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/get_vacancy/" + id;
        $.post(url, data, function (res){
            let urlform = '?jobgiver_id=' + res.vacancy.author_id.toString() + '&vacancy_id=' + res.vacancy.id.toString();
            $('#modal_title').text(res.vacancy.name);
            $('#modal_text').append('<p class="small-text disabled">' + res.vacancy.company_name  + '</p>'
                + '<p class="small-text disabled">требуемый опыт: ' + res.vacancy.expirience  + '</p>'
                +'<p class="small-text disabled">заработная плата: ' + res.vacancy.salary  + ' рублей</p>' + res.vacancy.description)
            if(res.vacancy.is_creator === false){
                $('#modal_footer').prepend('<a href="/sendmail/' + urlform + '" class="btn btn-primary" style="left: 10px; position: absolute;">Написать работодателю</a>');
            }
            if(res.is_favorite){
                $('#modal_footer').prepend('<button type="button" id="fv1" onclick="makeUnfavoriteJson(' + id + ')" type="button" class="btn btn-warning">В избранном</button>');
            }else{
                $('#modal_footer').prepend('<button id="fv2" onclick="makeFavoriteJson(' + id + ')" class="btn btn-outline-secondary"> В избранное</button>');
            }
            $('#spinner').hide();
        });
    }

    $("#jq_modal").on("hidden.bs.modal", function () {
        closeModal(event);
    });
    function closeModal(event){
        $('#jq_modal').modal('hide');
        $('#modal_text').empty();
        $('#fv2').remove();$('#fv1').remove();
    }
    function clearText(event){
        if(event.target.id === "fv2" || event.target.id === "fv1"){
            return;
        }
        $('#modal_text').empty();
        $('#fv2').remove();$('#fv1').remove();
    }

    function makeUnfavoriteJson(id){
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/make_unfav";
        $.post(url, data, function (res){
            $('#fv1').remove();
            $('#modal_footer').prepend('<button id="fv2" onclick="makeFavoriteJson(' + id + ')" class="btn btn-outline-secondary"> В избранное</button>');
        });
    }
    function makeFavoriteJson(id){
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/make_fav";
        $.post(url, data, function (res){
            $('#fv2').remove();
            $('#modal_footer').prepend('<button type="button" id="fv1" onclick="makeUnfavoriteJson(' + id + ')" type="button" class="btn btn-warning">В избранном</button>');
        });
    }
</script>
