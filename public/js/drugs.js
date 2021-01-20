/**
 * Auteur:
 * Date: Décembre 2020
 **/

function drugCheck(UID) {

    /*we have to use data attributes, since <p> can't use the value attribute. With this, we check whether value is
    undefined (in which case it is a <p>, and we don't need to update the value since it'll be static) and update data-value
    if it is
    */
    if(document.getElementById(UID + "start").value !== undefined) {
        document.getElementById(UID + "start").dataset.value = document.getElementById(UID + "start").value;
        document.getElementById(UID + "end").dataset.value = document.getElementById(UID + "end").value;
    }


    let expectedAmount = Number(document.getElementById(UID + "start").dataset.value);
    let endAmount = Number(document.getElementById(UID + "end").dataset.value);

    //pharmacheck?
    if(UID.indexOf("pharma") !== -1) {
        let novaCells = document.querySelectorAll("." + UID + ".nova");
        //not cells.forEach because then no way to get value out of callback function
        for(let i = 0; i < novaCells.length; i++) {
            if (novaCells[i].value !== undefined)
                novaCells[i].dataset.value = novaCells[i].value;
            expectedAmount -= Number(novaCells[i].dataset.value);
        }
    }
    if(endAmount !== expectedAmount) {
        document.getElementById(UID).style = "background-color: orange;"
    }
    else {
        document.getElementById(UID).removeAttribute("style");
    }

}