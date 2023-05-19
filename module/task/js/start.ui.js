/* If left = 0, warning. */
$('button[type="submit"]').on('click', function()
{
    var left     = parseFloat($("#left").val());
    var consumed = parseFloat($("#consumed").val());
    if(!left)
    {
        var result;
        if(!consumed)
        {
            alert(noticeTaskStart);
            result = false;
        }
        else
        {
            result = confirm(confirmFinish);
        }
        if(!result) return false;
    }
})
