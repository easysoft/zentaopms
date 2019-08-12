$(function()
{
    $('#result').change(function()
    {
        $.post(createLink('testcase', 'ajaxGetStatus', 'methodName=review&caseID=' + caseID), {result : $(this).val()}, function(status)
        {
            $('#status').val(status).change();
        });
    });
})
