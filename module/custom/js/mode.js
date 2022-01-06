$(function()
{
    $('#modeTab').addClass('btn-active-text');
    $('[name=mode]').change(function()
    {
        $(this).val() == 'new' ? $('#changeModeTips').removeClass('hidden') : $('#changeModeTips').addClass('hidden');
    });
})
