function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
$(function()
{
    $('#' + type + 'Tab').addClass('active');
})
