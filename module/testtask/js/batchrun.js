$(document).on('keyup', 'form input:text', function()
{
    var preSelect = $(this).parent().prev().find('select');
    if($(this).val() == '' && $(preSelect).val() == 'fail')
    {
        $(preSelect).val('pass');
    }
    else if($(this).val() != '' && $(preSelect).val() == 'pass')
    {
        $(preSelect).val('fail');
    }
})
