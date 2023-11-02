window.changeSpaceType = function()
{
    const type = $('[name=type]:checked').val();
    $('.ownerBox').toggleClass('hidden', type == 'private');
    $('.teamBox').toggleClass('hidden', type == 'private');
    $('.whitelistBox').toggleClass('hidden', type != 'private');
}

window.changeKanbanType = function()
{
    const type = $('[name=type]:checked').val();
    $('.params').toggleClass('hidden', type == 'private');
    $('.whitelistBox').toggleClass('hidden', type != 'private');
}

window.loadAllUsers = function()
{
    const link = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=0&field=owner&selectedUser=' + $('[name=owner]').val() + "&type=all");
    $.getJSON(link, function(data)
    {
        $('[name=owner]').zui('picker').render({items: data});
    });
}

window.changeKanbanSpace = function()
{
    const spaceID = $('[name=space]').val();
    if(spaceType == 'private')
    {
        const url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID='+ spaceID + '&field=whitelist&selectedUser=' + $('[name^=whitelist]').val());
        $.get(url, function(data)
        {
            $('[name^=whitelist]').zui('picker').render({items: data});
        });
    }
    else
    {
        let url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID='+ spaceID + '&field=team&selectedUser=' + $('[name^=team]').val());
        $.get(url, function(data)
        {
            $('[name^=team]').zui('picker').render({items: data});
        });

        url = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID='+ spaceID + '&field=owner&selectedUser=' + $('[name=owner]').val());
        $.get(url, function(data)
        {
            $('[name=owner]').zui('picker').render({items: data});
        });
    }
}
