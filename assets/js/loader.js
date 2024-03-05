// set delay 5s
var delay = 5000;

$(window).on('load', function () {
    setTimeout(function () {
        $("#loading").hide();
        $(".loader").hide();
    }, delay);
});