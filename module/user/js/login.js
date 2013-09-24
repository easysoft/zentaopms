function setForm(){}
$(document).ready(function()
{
    $('#account').focus();

    $("#lang").click(function(event) 
    {
        $("#langs").toggle();
        event.stopPropagation();
    });

    $(document).click(function(event)
    {
        $("#langs").hide();
        $("#qrcode").hide();
    });

    $("#langs a").click(function() 
    {
        $("#langs a").removeClass('active');
        $(this).addClass("active");
        selectLang($(this).attr("data-value"));
    });

    $("#mobile").click(function(event) {
        $("#qrcode").toggle();
        event.stopPropagation();
        return false;
    });
})
