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

                <div id="spinner" class="spinner-border" role="status" style="margin-left: 45%;">
                    <span class="sr-only"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id){
        $('#jq_modal').modal('show');
        $('#spinner').show();
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/get_article/" + id;
        $.post(url, data, function (res){
            $('#modal_title').text(res.article.header);
            let imgUrl = "{{asset('/storage/')}}/" + res.article.image_url;
            $('#modal_body').text(res.article.article_text).prepend('<img style="display: block;" id="modal_img" width="450" height="300" src="' + imgUrl + '">');
            $('#spinner').hide();
        });
    }
    function closeModal(){
        $('#jq_modal').modal('hide');
    }
</script>
