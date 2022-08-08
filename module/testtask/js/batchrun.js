$(function()
{
    if(from == 'testcase')
    {
        $("#navbar li[data-id='testtask']").toggleClass('active');
        $("#navbar li[data-id='testcase']").toggleClass('active');
    }
});

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
