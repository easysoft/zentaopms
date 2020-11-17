/* If left = 0, warning. */
function checkLeft()
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
        if(!result) setTimeout(function() {$.enableForm()}, 50);
        return result;
    }
}
