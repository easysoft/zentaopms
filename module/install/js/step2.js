$(document).ready(function()
{
    $.get("pathinfo.php", function(result)
    {
        $('#requestType').val('PATH_INFO');
    });
});     
