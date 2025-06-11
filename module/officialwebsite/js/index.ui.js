$().ready(function()
{
    getCaptchaContent($('.image-box'));
});

$('.image-box').on('click', function()
{
    getCaptchaContent($('.image-box'));
});

function getCaptchaContent($ele)
{
    $.get($.createLink('officialwebsite', 'getCaptcha'), function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success') $ele.html(response.captchaContent);
    });
}