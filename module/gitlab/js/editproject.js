$(function()
{
    if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);

    $('input:radio[name="visibility"]').change(function()
    {
        var visibility = $('input:radio[name="visibility"]:checked').val();
        if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
        if(visibility != 'public') $('#publicTip').remove();
    })
})
