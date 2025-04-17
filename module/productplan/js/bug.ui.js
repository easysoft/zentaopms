window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#planBugs'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
