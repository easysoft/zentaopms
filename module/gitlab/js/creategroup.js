$(function()
{
    $("input[type='radio'][value='public']").parent().parent().css("margin-bottom", "0px");

    $('input:radio[name="visibility"]').change(function()
    {
        var visibility = $('input:radio[name="visibility"]:checked').val();
        if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
        if(visibility != 'public') $('#publicTip').remove();
    })
})
