function checkall(checker, id)
{
    $('#' + id + ' input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
