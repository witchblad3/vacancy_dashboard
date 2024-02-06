<style>
    .mail-info{
        background-color: #b6b6b6;
        border-radius: 5px;
        margin: 0;
        border: 6px solid #b6b6b6;
        display: grid;
    }
</style>

<div class="modal fade" id="mail_modal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button onclick="closeMailModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="mail-info">
                    <span id="from"></span>
                    <span id="to"></span>
                </div>
                <div id="mail_text">
                    текст письма
                </div>

                <div id="mail_spinner" class="spinner-border" role="status" style="margin-left: 45%;">
                    <span class="sr-only"></span>
                </div>
            </div>
            <div class="modal-footer">
                <a id="reply" type="button" class="btn btn-warning" data-dismiss="modal">Ответить</a>
                <button onclick="closeMailModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openMailModal(id){
        $('#mail_modal').modal('show');
        $('#mail_spinner').show();
        let data = {
            id: id,
            _token: "{{csrf_token()}}"
        }
        let url = "/get_mail";
        $.post(url, data, function (res){
            console.log(res);
            let formUrl = '/mail/reply?to=' + res.mail.sender_id + '&mail_id=' + res.mail.id;
            $('#reply').attr('href', formUrl)
            if(res.user_id === res.mail.sender_id){
                $('#reply').remove();
            }
            $('#modal_title').text(res.mail.subject);
            $('#from').text("От: " + res.mail.sender.first_name + " " + res.mail.sender.last_name);
            $('#to').text("Кому: " + res.mail.recipient_object.first_name + " " + res.mail.recipient_object.last_name);
            $('#mail_text').text(res.mail.mail_text);
            $('#mail_spinner').hide();

            $('#' + res.mail.id + '_new').remove();
        });
    }
    function closeMailModal(){
        $('#mail_modal').modal('hide');
    }
</script>
