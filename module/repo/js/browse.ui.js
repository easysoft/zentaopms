window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        var iconHtml = '<span class="' + (row.data.kind == 'dir' ? 'directory' : 'file') + ' mini-icon"></span>';
        result[0] = {html:iconHtml + '<a href="' + row.data.link + '">' + row.data.name + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};

window.renderCommentCell = function(result, {col, row})
{
    if(col.name === 'revision')
    {
        result[0] = {html:'<a href="' + row.data.link + '">' + row.data.revision + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};

/* Open download page when downZip btn click. */
$('.downloadZip-btn').on('click', function()
{
    var link = $.createLink('repo', 'downloadCode', 'repoID=' + repoID + '&branch=' + branch);
    window.open(link);
})

/* Refresh page when repo changed. */
$('#repo-select').on('change', function()
{
    var index = $('#repo-select').prop('selectedIndex');
    if(menus[index - 1].url != undefined)
    {
        window.location.href = menus[index - 1].url;
    }
})

/**
 * 当选中两行时禁用其他行。
 * Disable checkable attribution when checked rows equal 2.
 * 
 * @param  object changes
 * @access public
 * @return void
 */
window.checkedChange = function(changes)
{
    const dtable   = zui.DTable.query('#repo-comments-table');
    var checkedIds = dtable == undefined ? [] : dtable.$.getChecks()

    if(checkedIds.length < 2)
    {
        $('.btn-diff').addClass('disabled')
    }
    else
    {
        $('.btn-diff').removeClass('disabled')
    }
}

window.diffClick = function()
{
    const dtable    = zui.DTable.query('#repo-comments-table');
    var checkedIds  = dtable.$.getChecks();
    var newDiffLink = diffLink.replace('{oldRevision}', revisionMap[checkedIds[1]]);
    newDiffLink     = newDiffLink.replace('{newRevision}', revisionMap[checkedIds[0]]);

    console.log('checkedIds:', checkedIds);

    window.location.href = newDiffLink;
}

$(function()
{
    setTimeout(function()
    {
        const dtable = zui.DTable.query('#repo-comments-table');
        dtable.$.toggleCheckRows(Object.keys(revisionMap).slice(0, 2));
        window.checkedChange();
    }, 100);
})