function mailForm(id){
    $('#user-'+ id).find(".inputMail").val($('#user-'+ id).find(".mail").html());
    $('#user-'+ id).find(".displayMail").hide();
    $('#user-'+ id).find(".updateMail").show();
}

function mailUpdate(id){
    var mail = $('#user-'+ id).find(".inputMail").val();
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
            resetMail(id)
            console.error(xhr);
        }
    });
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
            resetTel(id)
            console.error(xhr);
        }
    });
}

function resetTel(id){
    $('#user-'+ id).find(".displayTel").show();
    $('#user-'+ id).find(".updateTel").hide();
}