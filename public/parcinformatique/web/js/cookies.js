$('#cookie-banner').addClass('cookie-banner');
$('#cookie-banner').addClass('full');
$('#cookie-banner').addClass('top');
setTimeout(function () {
    $('#cookie-banner').show().delay(200);
}, 100);
$("#btOkCookies").on("click", function () {
    $('#cookie-banner').hide();
});