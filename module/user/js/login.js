function setForm(){}
$(document).ready(function()
{
    $('#account').focus();
    var iframe = document.getElementById("updater");
    iframe.src = url;
    if (iframe.attachEvent)
    {
        iframe.attachEvent("onload", function(){$('#updater').show();});
    }
    else
    {
        iframe.onload = function(){$('#updater').show();};
    }
})
