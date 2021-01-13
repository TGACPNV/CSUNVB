/**
 * Auteur:
 * Date: Décembre 2020
 **/

function cellUpdate(UID) {
    let expectedAmount = Number(document.getElementById(UID + "start").value);
    let endAmount = Number(document.getElementById(UID + "end"));

    //novacheck?
    if(UID.indexOf("nova")) {
        let novaCells = document.querySelectorAll(UID + ".nova");

        //not cells.forEach because then no way to get value out of callback function
        for(let i in novaCells) {
            expectedAmount -= Number(cells[i].value);
        }
    }

    if(endAmount !== expectedAmount) {
        checkFailed(UID);
    }
    else {
        checkPassed(UID);
    }
}

function checkFailed(UID) {
    document.getElementById(UID).style = "background-color: orange;"
}

function checkPassed(UID) {
    document.getElementById(UID).removeAttribute("style");
}

function test() {
    let n = 0;
    cells = [21, 5324, 543]
    for(let i in cells) {
        n += cells[i];
        console.log(i);
    }
    console.log(n);
}

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