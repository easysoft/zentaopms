$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('storyIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 对标题列进行重定义。
 * Redefine the title column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, {row, col})
{
    if(col.name == 'title' && result[0])
    {
        const story = info.row.data;
        if(story.shadow == 1) result[0].props.href += '#app=project';

        let html       = '';
        let gradeLabel = '';
        if(showGrade || story.grade >= 2) gradeLabel = gradeGroup[story.type][story.grade]?.name;
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(story.color) result[0].props.style = 'color: ' + story.color;
        if(html) result.unshift({html});
    }

    if(col.name == 'actions')
    {
        for(index in row.data.actions)
        {
            if(row.data.actions[index].name == 'recall') row.data.actions[index].hint = row.data.status == 'changing' ? recallChange : recall;
        }
    }
    return result;
}

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var storyViewType = $(this).attr('data-type');
    $.cookie.set('storyViewType', storyViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});
