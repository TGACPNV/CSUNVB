/**
 * Auteur:
 * Date: Décembre 2020
 **/


function cellUpdate(UID, time, value) {
    document.GetElementById("save").style.visibility = visible;
    /*
    document.cookie = UID + time "=" + document.getElementById("UID" + time).value;
    drugCheck(UID);
    */
}

function sendData

function drugCheck(UID) {
    let expectedAmount = Number(document.getElementById(UID + "start").value);
    let endAmount = Number(document.getElementById(UID + "end").value);

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