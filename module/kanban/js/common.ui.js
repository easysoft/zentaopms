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

    const url = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + type);
    //loadPartial(url, '#teamBox');
}

window.loadAllUsers = function()
{
    const link = $.createLink('kanban', 'ajaxLoadUsers', 'spaceID=0&field=owner&selectedUser=' + $('[name=owner]').val() + "&type=all");
    $.getJSON(link, function(data)
    {
        $('[name=owner]').zui('picker').render({items: data});
    });
}
