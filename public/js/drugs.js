/**
 * Auteur:
 * Date: Décembre 2020
 **/

function novaCheck(novaID, drugID, dateID) {
    divID = novaID + drugID + dateID;
    let originalQuantity = document.getElementById(divID + "start").value;
    let currentQuantity = document.getElementById(divID + "end").value;
    quantityCheck(divID, originalQuantity, currentQuantity);
}

function pharmaCheck(drugID, dateID) {
    divID = drugID + dateID;
    let originalQuantity = document.getElementById(divID + "start").value;
    let currentQuantity = document.getElementById(divID + "end").value; // + document.getElementById(divID + "day").value + document.getElementById(divID + "night").value;
    quantityCheck(divID, originalQuantity, currentQuantity);
}

function quantityCheck(divID, originalQuantity, currentQuantity) {

    if(Number(currentQuantity) !== Number(originalQuantity)) {
        document.getElementById(divID).style = "background-color: orange;"
    } else {
        document.getElementById(divID).removeAttribute("style");
    }
}