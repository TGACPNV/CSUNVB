/**
 * Auteur:
 * Date: Décembre 2020
 **/

function novaCheck(novaID, drugID, dateID) {
    divID = novaID + drugID + dateID;
    quantityCheck(divID);
}

function pharmaCheck(drugID, dateID) {
    divID = drugID + dateID;
    quantityCheck(divID);
}

function quantityCheck(divID) {
    let originalQuantity = document.getElementById(divID + "start").value;
    let currentQuantity = document.getElementById(divID + "end").value;
    if(Number(currentQuantity) !== Number(originalQuantity)) {
        document.getElementById(divID).style = "background-color: orange;"
    } else {
        document.getElementById(divID).removeAttribute("style");
    }
}