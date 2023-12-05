var revisionMap = {};
var checkedIds  = [];

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        var iconHtml = '<span class="' + (row.data.kind == 'dir' ? 'directory' : 'file') + ' mini-icon"></span>';
        result[0] = {html:iconHtml + '<a href="' + row.data.link + '" data-app="' + appTab + '">' + row.data.name + '</a>'};

        return result;
    }

    if(col.name === 'originalComment')
    {
        result[0] = {html:'<span class="repo-comment">' + row.data.comment + '</span>'};

        return result;
    }

    return result;
};

window.afterRender = function()
{
    if(repo.SCM != 'Gitlab') return;

    const dtable  = $('#table-repo-browse').zui('dtable');
    if(!dtable) return;

    const oldData = dtable.options.data;;
    if(oldData.length == 0) return;

     // 如果正在加载提交信息或已经加载提交信息，直接返回
    if(dtable.isLoadingCommits || dtable.isLoadedAllCommits) return;

    // 设置正在加载提交信息
    dtable.isLoadingCommits = true;

    // 获取下一个需要加载提交信息的行
    const nextCommitRowIndex = oldData.findIndex(row => !row.revision);
    if(nextCommitRowIndex < 0) {
        // 如果没有需要加载提交信息的行，设置已经加载提交信息
        dtable.isLoadedAllCommits = true;
        return;
    }

    const nextCommitRow = oldData[nextCommitRowIndex];
    $.post(
        $.createLink('repo', 'ajaxGetFileCommitInfo'),
        {repoID: repo.id, branch: branch, path: nextCommitRow.path}
    ).then(rowData =>
    {
        const commit = JSON.parse(rowData);

        // 取消设置正在加载提交信息
        dtable.isLoadingCommits = false;

        // 合并行数据和提交信息
        oldData[nextCommitRowIndex].date     = commit.authoredDate;
        oldData[nextCommitRowIndex].account  = commit.author ? commit.author.username : commit.authorName;
        oldData[nextCommitRowIndex].comment  = commit.comment;
        oldData[nextCommitRowIndex].revision = commit.sha;

        // 重新渲染表格
        dtable.render({data: oldData});
    });
}

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
        result[0] = {html:'<a href="' + row.data.link + '" data-app="' + appTab + '">' + row.data.revision + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    if(col.name === 'originalComment')
    {
        result[0] = {html:'<span class="repo-comment">' + row.data.comment + '</span>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};

/* Open download page when downZip btn click. */
$('.downloadZip-btn').on('click', function()
{
    var link = $.createLink('repo', 'downloadCode', 'repoID=' + repo.id + '&branch=' + branch);
    window.open(link);
})

/* Refresh page when repo changed. */
$('#repo-select').on('change', function()
{
    var index = $('#repo-select').prop('selectedIndex');
    if(menus[index - 1].url != undefined)
    {
        openUrl(menus[index - 1].url, {app: appTab});
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
    checkedIds = getCurrentCheckedIds();

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
    var checkedIds = getCurrentCheckedIds();
    if(checkedIds.length < 2) return;

    var newDiffLink = diffLink.replace('{oldRevision}', revisionMap[checkedIds[1]]);
    newDiffLink     = newDiffLink.replace('{newRevision}', revisionMap[checkedIds[0]]);

    $.cookie.set('sideRepoSelected', checkedIds.join(','))

    openUrl(newDiffLink, {app: appTab});
}

/**
 * 当选中数量等于2，则禁用其他所有行。
 * When the selected row equals 2, disable all other rows.
 *
 * @param int     rowID
 * @access public
 * @return bool
 */
window.canRowCheckable = function(rowID)
{
    const dtable = zui.DTable.query('#repo-comments-table');
    if(dtable == undefined) return;
    var data = dtable.$.props.data;

    if(data.length == 0) return true;

    initRevisionMap(data);

    var currentCheckedIds = getCurrentCheckedIds();

    if(currentCheckedIds.length < 2)           return true;
    if(currentCheckedIds.indexOf(rowID) == -1) return 'disabled'

    return true;
}

/**
 * 检测revisionMap是否跟当前页面数据一致，不一致重新生成。
 * Regenerate revisionMap when revisionMap is not in current list.
 *
 * @param  array
 * @access public
 * @return void
 */
function initRevisionMap(data)
{
    if(revisionMap[data[data.length - 1].id] !== undefined && revisionMap[data[0].id] !== undefined) return;

    revisionMap = {};
    for (var i = 0; i < data.length; i++) revisionMap[data[i].id] = data[i].revision;

    /* Check rows where id in cookie. */
    setTimeout(function()
    {
        checkColInCurrentPage()
    }, 100);
}

/**
 * 选中当前页码对应的行。
 * Select the rows to the current page.
 *
 * @access public
 * @return void
 */
function checkColInCurrentPage()
{
    const dtable     = zui.DTable.query('#repo-comments-table');
    const checkedIds = $.cookie.get('sideRepoSelected') ? $.cookie.get('sideRepoSelected').split(',') : [];


    var currentCheckedIds = [];
    if(revisionMap[checkedIds[0]]) currentCheckedIds.push(checkedIds[0]);
    if(revisionMap[checkedIds[1]]) currentCheckedIds.push(checkedIds[1]);

    dtable.$.toggleCheckRows(Object.keys(revisionMap), false);

    if(currentCheckedIds.length > 0)
    {
        dtable.$.toggleCheckRows(currentCheckedIds, true);
    }
    else
    {
        dtable.$.toggleCheckRows(Object.keys(revisionMap).slice(0, 2), true);
    }

    window.checkedChange();
}

/**
 * 获取当前页码选中的行。
 * Get checked rows in current page.
 *
 * @access public
 * @return array
 */
function getCurrentCheckedIds()
{
    const dtable = zui.DTable.query('#repo-comments-table');
    if(dtable.$ == undefined) return [];

    var   checkedIds        = dtable.$.getChecks();
    var   currentCheckedIds = [];

    for (var i = 0; i < checkedIds.length; i++)
    {
        if(revisionMap[checkedIds[i]]) currentCheckedIds.push(checkedIds[i]);
    }

    if(currentCheckedIds.length > 2) currentCheckedIds = currentCheckedIds.slice(0, 2);
    return currentCheckedIds;
}

$('.copy-btn').on('click', function()
{
    var copyText = $(this).parent().parent().find('input');
    copyText[0].select();
    document.execCommand("Copy");
    copyText[0].selectionStart = copyText[0].selectionEnd;
    copyText[0].blur();

    $(that).tooltip('show');
    var that = this;
    setTimeout(function()
    {
        $(that).tooltip('hide');
    }, 2000)
})

window.afterPageUpdate = function()
{
    setTimeout(function()
    {
        $('.copy-btn').tooltip({
            trigger: 'click',
            placement: 'bottom',
            title: copied,
            tipClass: 'success',
        });
    }, 200);
};
