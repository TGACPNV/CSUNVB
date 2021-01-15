/**
 * Le fichier contient les fonctionnalités javascript qui ne sont utilisées que pour les tâches à réaliser
 * Auteur: Vicky Butty
 * Date: Décembre 2020
 **/

// formulaire de vérification pour todoModal
var buttons = document.querySelectorAll('.toggleTodoModal');

buttons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#todoModal").modal("toggle");
        document.getElementById("modal-validationTitle").innerHTML = this.getAttribute("data-title");
        document.getElementById("modal-validationContent").innerHTML = this.getAttribute("data-content");
        document.getElementById("modal-todoID").value = this.getAttribute("data-id");

        var status = this.getAttribute("data-status");
        var type = this.getAttribute("data-type");

        if(type == "2" && status == "close"){
            document.getElementById("modal-todoValue").type = "text";
        }

        document.getElementById("modal-todoType").value = type;
        document.getElementById("modal-todoStatus").value = status;

    }, false);
})
console.log(buttons.length);

//
var trashButtons = document.querySelectorAll('.trashButtons');

trashButtons.forEach((item) => {
    item.addEventListener('click', function (event) {
        $("#deletingTaskModal").modal("toggle");
        document.getElementById("modal-deletingTitle").innerHTML = this.getAttribute("data-title");
        document.getElementById("modal-deletingContent").innerHTML = this.getAttribute("data-content");
        document.getElementById("modal-deletingTaskID").value = this.getAttribute("data-id");

    }, false);
})
