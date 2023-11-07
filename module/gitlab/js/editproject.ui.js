function onAclChange(event)
{
    const visibility = $(event.target).val();

    if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
    if(visibility != 'public') $('#publicTip').remove();
}

$(function()
{
    if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
});
