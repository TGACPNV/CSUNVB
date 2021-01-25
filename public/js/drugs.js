/**
 * Auteur:
 * Date: Décembre 2020
 **/


function cellUpdate(UID, tag) {
    document.getElementById("save").removeAttribute("hidden");
    //document.cookie = "drug" + UID + tag + "=" + document.getElementById(UID + tag).value;
    drugCheck(UID);
}

function sendData() {
    window.open("?action=updateDrugSheet", "_self");
}

function drugCheck(UID) {
    let expectedAmount = Number(document.getElementById(UID + "_start").value);
    let endAmount = Number(document.getElementById(UID + "_end").value);

    //pharmacheck?
    if(UID.indexOf("pharma") !== -1) {
        let novaCells = document.querySelectorAll("." + UID + ".nova");
        //not cells.forEach because then no way to get value out of callback function
        for(let i = 0; i < novaCells.length; i++) {
            expectedAmount -= Number(novaCells[i].value);
        }
    }
    if(endAmount !== expectedAmount) {
        document.getElementById(UID).style = "background-color: orange;"
    }
    else {
        document.getElementById(UID).removeAttribute("style");
    }
}