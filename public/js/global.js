var dropdownButtons = document.querySelectorAll('.dropdownButton');

dropdownButtons.forEach((item) => {
    item.addEventListener('click', function (event) {
        var state = event.target.dataset.list;
        var elements = document.getElementsByClassName(state+"Sheets");

        for (var i = 0; i < elements.length; i ++) {
            if(elements[i].classList.contains('d-none')){
                elements[i].classList.remove('d-none');
            }else {
                elements[i].classList.add('d-none');
            }
        }
    }, false);
})

function print_page(){
    window.print();
}

function flashMessage(message){
    $("#flashMessage").html('<div class="alert alert-info">'+message+'</div>');
}
