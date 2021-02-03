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