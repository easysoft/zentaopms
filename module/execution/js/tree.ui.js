window.changeDisplay = function()
{
    //console.log($(this).is(':checked'))
}

window.loadObject = function({event, item})
{
    if(!$(event.target).hasClass('state') && !$(event.target).hasClass('caret-down') && item.content.link)
    {
        $('#taskTree .tree-item-content').removeClass('active');
        $(event.target).closest('.tree-item-content').addClass('active');

        $('#detailBlock').load(item.content.link);
        $('#detailBlock').removeClass('hidden');
    }
}
