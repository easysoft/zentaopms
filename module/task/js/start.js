/* If left = 0, warning. */
function checkLeft()
{
    var value = $("#left").val();
    if(isNaN(parseFloat(value)) || value == 0)
    {
        var result = confirm(confirmFinish);
        if(!result) setTimeout(function() {$.enableForm()}, 500);
        return result;
    }
}
