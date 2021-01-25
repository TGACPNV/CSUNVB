/**
 * Le fichier shift.js contient les fonctionnalités javascript qui ne sont utilisées que pour la remise de garde
 * Auteur: Michael Gogniat et Paola Costa
 * Date: Décembre 2020
 **/

// formulaire de vérification pour shiftModal
var buttons = document.querySelectorAll('.toggleShiftModal');

buttons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#shiftModal").modal("toggle");
        $("#action_id").val( this.getAttribute("data-action_id"));
        $("#day").val( this.getAttribute("data-day"));
        $("#modal-content").html(this.getAttribute("data-content"));
        $("#shiftSheetinfo").attr('action', this.getAttribute("data-action"));
        $("#comment").prop("type",this.getAttribute("data-comment"));
    }, false);
})

var addCarryOnBtn = document.querySelectorAll('.addCarryOnBtn');
addCarryOnBtn.forEach((item) => {
    item.addEventListener('click', function (event) {
        $( "#comment-" + this.value ).removeClass( "notCarry" );
        $( "#comment-" + this.value ).addClass( "carry" );
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
            }
        };
        request.open("GET", "?action=addCarryOnComment&id="+ this.value, true);
        request.send();
    }, false);
})


var removeCarryOnBtn = document.querySelectorAll('.removeCarryOnBtn');
removeCarryOnBtn.forEach((item) => {
    item.addEventListener('click', function (event) {
        $( "#comment-" + this.value ).removeClass( "carry" )
        $( "#comment-" + this.value ).addClass( "notCarry" )
        $.ajax({
            type: "POST",
            url: "?action=carryOffComment",
            data: {
                carryOff: $("#shiftDate").val(),
                commentID: this.value
            },
            cache: false,
            success: function(data) {
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }, false);
})

$( ".SH_dropdownInfo" ).change(function() {
    $( "#SH_updateInfoBtn").show();
});
