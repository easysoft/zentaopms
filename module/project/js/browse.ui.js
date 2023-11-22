window.programMenuOnClick = function(data, url)
{
    location.href = url.replace('%d', data.item.key);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.delay > 0) result[result.length] = {html:'<span class="label size-sm circle danger-pale">' + langPostponed + '</span>', className:'flex items-end w-full', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'storyCount')
    {
        result[result.length] = {html:'<span class="text-gray text-xs">SP</span>'};
        return result;
    }

    if(col.name === 'invested')
    {
        result[result.length] = {html:'<span class="text-gray text-xs">' + langManDay + '</span>'};
        return result;
    }

    return result;
}

$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $(this).data('url');
    checkedList.forEach((id) => form.append('projectIdList[]', id));
    postAndLoadPage(url, form);
}).off('click', '#actionBar .export').on('click', '#actionBar .export', function()
{
    const dtable = zui.DTable.query($('#table-project-browse'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList);
});

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var projectType = $(this).attr('data-type');
    $.cookie.set('projectType', projectType);
    loadCurrentPage();
});

$(document).on('click', 'input[name=involved]', function()
{
    var involved = $(this).is(':checked') ? 1 : 0;
    $.cookie.set('involved', involved, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});
