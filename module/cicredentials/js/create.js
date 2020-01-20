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

        $('#privateKey-field').hide();
        $('#passphrase-field').hide();
        $('#token-field').hide();
    }
    else if (type == 'token')
    {
        $('#token-field').show();

        $('#privateKey-field').hide();
        $('#passphrase-field').hide();
        $('#password-field').hide();
    }
    else // sshKey
    {
        $('#privateKey-field').show();
        $('#passphrase-field').show();

        $('#password-field').hide();
        $('#token-field').hide();
    }
}