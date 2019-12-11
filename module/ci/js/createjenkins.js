$(function()
{
    $('#' + module + 'Tab').addClass('btn-active-text');

    showByType(type);
    $('#type').on('change', function()
    {
        showByType($(this).val());
    });
});

function showByType(type) {
    if(type == 'account')
    {
        $('#password-field').show();
        $('#token-field').hide();
    }
    else
    {
        $('#token-field').show();
        $('#password-field').hide();
    }
}