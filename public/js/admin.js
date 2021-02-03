function mailForm(id){
    $('#user-'+ id).find(".inputMail").val($('#user-'+ id).find(".mail").html());
    $('#user-'+ id).find(".displayMail").hide();
    $('#user-'+ id).find(".updateMail").show();
}

function mailUpdate(id){
    var mail = $('#user-'+ id).find(".inputMail").val();
    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if(mail.match(mailformat))
    {
        $.ajax({
            type: "POST",
            url: "?action=changeEmail",
            data: {
                userID: id,
                mail: mail
            },
            cache: false,
            success: function(data) {
                $('#user-'+ id).find(".mail").html(mail);
                $('#user-'+ id).find(".displayMail").show();
                $('#user-'+ id).find(".updateMail").hide();
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    else
    {
        $('#user-'+ id).find(".inputMail").val($('#user-'+ id).find(".mail").html());
    }
}

function resetMail(id){
    $('#user-'+ id).find(".displayMail").show();
    $('#user-'+ id).find(".updateMail").hide();
}

function telForm(id){
    $('#user-'+ id).find(".inputTel").val($('#user-'+ id).find(".tel").html());
    $('#user-'+ id).find(".displayTel").hide();
    $('#user-'+ id).find(".updateTel").show();
}

function telUpdate(id){
    var tel = $('#user-'+ id).find(".inputTel").val();
    var telformat = /^[0-9 +-]{9,11}$/;
    if(tel.match(telformat)){
        $.ajax({
            type: "POST",
            url: "?action=changeTel",
            data: {
                userID: id,
                tel: tel
            },
            cache: false,
            success: function(data) {
                $('#user-'+ id).find(".tel").html(tel);
                $('#user-'+ id).find(".displayTel").show();
                $('#user-'+ id).find(".updateTel").hide();
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }else{
        $('#user-'+ id).find(".inputTel").val($('#user-'+ id).find(".tel").html());
    }

}

function resetTel(id){
    $('#user-'+ id).find(".displayTel").show();
    $('#user-'+ id).find(".updateTel").hide();
}