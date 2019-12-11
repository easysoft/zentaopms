$(function()
{
    $('#' + module + 'Tab').addClass('btn-active-text');

    showByType(type);
    $('#type').on('change', function()
    {
        showByType($(this).val());
    });
})

function showByType(type) {
    if(type == 'account')
    {
        $('#password-field').show();

        $('#privateKey-field').hide();
        $('#passphrase-field').hide();
    }
    else
    {
        $('#privateKey-field').show();
        $('#passphrase-field').show();

        $('#password-field').hide();
    }
}