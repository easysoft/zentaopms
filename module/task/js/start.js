/* If left = 0, warning. */
function checkLeft()
{
    var left     = $("#left").val();
    var consumed = $("#consumed").val();
    if(isNaN(parseFloat(left)) || left == 0)
    {
        if(isNaN(parseFloat(consumed)) || consumed == 0)
        {
            alert(noticeTaskStart);
            return false;
        }
        var result = confirm(confirmFinish);
        if(!result) setTimeout(function() {$.enableForm()}, 500);
        return result;
    }
}
