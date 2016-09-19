$(document).ready(function(){
    //при нажатию на кнопку с id = login_button
    $("#login_button").click(function() {
        //открыть модальное окно с id="loginModal"
        $("#loginModal").modal('show');
    });

    //при нажатию на кнопку с id = reg_button
    $("#reg_button").click(function() {
        //открыть модальное окно с id="loginModal"
        $("#regModal").modal('show');
    });

});
