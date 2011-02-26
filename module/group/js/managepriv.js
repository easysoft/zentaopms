function check(checker, module)
{
    $('#' + module + ' input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
