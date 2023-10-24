$(function(){
    $("#toSeniorForm input#readtrue").on('change', function()
    {
        if($('#toSeniorForm input#readtrue:checked').length > 0)
        {
            $('#toSeniorForm button[type=submit]').attr('disabled', false);
        }
        else
        {
            $('#toSeniorForm button[type=submit]').attr('disabled', true);
        }
    });
    $("#toSeniorForm input#readtrue").change();
});
