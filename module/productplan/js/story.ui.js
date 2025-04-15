window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        let gradeLabel = gradeGroup[story.type][story.grade];
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }

    return result;
};

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#planStories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
