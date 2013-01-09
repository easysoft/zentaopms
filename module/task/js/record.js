/* If left = 0, warning. */
function checkLeft()
{
    value = $("#left").val();
    if(isNaN(parseInt(value))) 
    {
        if(confirm(confirmFinish))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
