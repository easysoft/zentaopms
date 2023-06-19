window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        var iconHtml = '<span class="' + (row.data.kind == 'dir' ? 'directory' : 'file') + ' mini-icon"></span>';
        result[0] = {html:iconHtml + '<a href="' + row.data.link + '">' + row.data.name + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    if(col.name === 'comment')
    {
        result[0] = {html:'<span class="repo-comment">' + row.data.comment + '</span>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};

/**
 * commit表格渲染跳转链接。
 * Render jump link of version.
 *
 * @access public
 * @return void
 */
window.renderCommentCell = function(result, {col, row})
{
    if(col.name === 'revision')
    {
        result[0] = {html:'<a href="' + row.data.link + '">' + row.data.revision + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    if(col.name === 'originalComment')
    {
        result[0] = {html:'<span class="repo-comment">' + row.data.originalComment + '</span>', style:{flexDirection:"column"}};

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

/**
 * 跳转比较差异页面。
 * Redirect to diff page.
 *
 * @access public
 * @return void
 */
window.diffClick = function()
{
    const dtable    = zui.DTable.query('#repo-comments-table');
    var checkedIds  = dtable.$.getChecks();
    var newDiffLink = diffLink.replace('{oldRevision}', revisionMap[checkedIds[1]]);
    newDiffLink     = newDiffLink.replace('{newRevision}', revisionMap[checkedIds[0]]);

    $.cookie.set('sideRepoSelected', checkedIds.join(','))

    window.location.href = newDiffLink;
}

/**
 * 当选中数量等于2，则禁用其他所有行。
 * When the selected row equals 2, disable all other rows.
 *
 * @param int     rowID
 * @access public
 * @return void
 */
window.canRowCheckable = function(rowID)
{
    const dtable    = zui.DTable.query('#repo-comments-table');
    var checkedIds  = dtable.$.getChecks();

    if(checkedIds.length < 2)           return true;
    if(checkedIds.indexOf(rowID) == -1) return 'disabled'

    return true;
}

$(function()
{
    /* Checked the first and second row when page loaded. */
    setTimeout(function()
    {
        const dtable = zui.DTable.query('#repo-comments-table');
        if($.cookie.get('sideRepoSelected'))
        {
            var sideRepoSelectedAry = $.cookie.get('sideRepoSelected').split(',');
            dtable.$.toggleCheckRows(sideRepoSelectedAry);
        }
        else
        {
            dtable.$.toggleCheckRows(Object.keys(revisionMap).slice(0, 2));
        }

        window.checkedChange();
    }, 100);
})

$('.copy-btn').on('click', function()
{
    var copyText = $(this).parent().parent().find('input');
    console.log(copyText);
    copyText[0].select();
    console.log(document.execCommand("Copy"))
    document.execCommand("Copy");

    $(this).tooltip('show');
    var that = this;
    setTimeout(function()
    {
        $(that).tooltip('hide')
    }, 2000)
})