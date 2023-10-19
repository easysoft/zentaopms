window.changeDisplay = function()
{
    $('#taskTree .tree-item-content.task').toggleClass('hidden', $(this).is(':checked'));
}

window.loadObject = function({event, item})
{
    if(!$(event.target).hasClass('state') && !$(event.target).hasClass('caret-down') && item.url)
    {
        $('#taskTree .tree-item-content').removeClass('active');
        $(event.target).closest('.tree-item-content').addClass('active');

        loadTarget(item.url, '#detailBlock');
        $('#detailBlock').removeClass('hidden');
    }
}
