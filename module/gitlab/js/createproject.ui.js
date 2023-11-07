function onNameChange(event)
{
    $("#path").val($(event.target).val().toLowerCase());
}

function onAclChange(event)
{
    const visibility = $(event.target).val();

    if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
    if(visibility != 'public') $('#publicTip').remove();
}
