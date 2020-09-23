$(function()
{
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#end').val('').attr('disabled', 'disabled');
            $('#days').val('');
        }
        else
        {
            $('#end').removeAttr('disabled');
        }
    });
});
