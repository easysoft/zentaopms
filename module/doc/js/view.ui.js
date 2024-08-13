$(function()
{
    if($.cookie.get('hiddenOutline') == 'true') $("#docPanel").addClass("show-outline")
});

$("#docContent").on('enterFullscreen', () => {
    $('.right-icon').attr('id', 'right-icon').removeClass('right-icon');
});

$("#docContent").on('exitFullscreen', () => {
    $('#right-icon').addClass('right-icon');
});
