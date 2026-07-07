var revisionMap = {};
var checkedIds  = [];

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        const iconHtml = '<i class="icon card-icon mr-2 icon-' + (row.data.kind == 'dir' ? 'folder text-warning' : 'file-text-alt') + '"></i>';
        result[0] = {html: iconHtml + '<a href="' + row.data.link + '" data-app="' + appTab + '">' + row.data.name + '</a>', className: row.data.account ? '' : 'hidden'};

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

    const dtable = $('#table-repo-browse').zui('dtable');
    if(!dtable) return;

    const oldData = dtable.options.data;;
    if(oldData.length == 0) return;

    // 如果正在加载提交信息或已经加载提交信息，直接返回
    if(!dtable.repoID || dtable.repoID != repo.id || !dtable.branch || dtable.branch != base64BranchID || !dtable.path || dtable.path != path)
    {
        dtable.isLoadingCommits   = false;
        dtable.isLoadedAllCommits = false;
    }

    dtable.repoID = repo.id;
    dtable.path = path;
    dtable.branch = base64BranchID;
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
window.downloadZip = function()
{
    var link = $.createLink('repo', 'downloadCode', 'repoID=' + repo.id + '&branch=' + branch);
    $.ajaxSubmit({url: link});
}

window.loadSSHmanager = function()
{
    var link = $.createLink('my', 'ssh', 'repoID=' + repoID + '&objectID=' + objectID) + '#app=' + appTab;
    openUrl(link);
}

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
window.checkedChange = function()
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

    $.cookie.set('sideRepoSelected', checkedIds.join(','), {expires:config.cookieLife, path:config.webRoot})

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

window.copyLink = function(dom)
{
    var copyText = $(dom).parent().parent().find('input');
    copyText[0].select();
    document.execCommand("Copy");
    copyText[0].selectionStart = copyText[0].selectionEnd;
    copyText[0].blur();

    zui.Messager.show({
        type:    'success',
        content: copied,
        time:    2000
    });
}

$(function()
{
    if(base64BranchID) $.get($.createLink('repo', 'ajaxSyncBranchCommit', 'repoID=' + repo.id + '&branch=' + base64BranchID));
});


function mirrorBusy($btn, on)
{
    $btn.prop('disabled', !!on).toggleClass('disabled', !!on);
    $btn.find('i.icon').toggleClass('spin', !!on);
}

function mirrorReload()
{
    setTimeout(function()
    {
        if(typeof loadPage === 'function') return loadPage({selector: '#main>*'});
        window.location.reload();
    }, 600);
}

function mirrorParse(res){ return typeof res === 'string' ? JSON.parse(res) : res; }

/* 同步触发→刷新延时（ms）。GitFox 异步处理，立即拉进度可能拿不到最新 status；留 1500ms 缓冲，最常见场景下一次 fetch 就能拿到 syncing 或 finished。 */
window.MIRROR_SYNC_RELOAD_DELAY = 1500;

/**
 * ajax-submit 成功回调：延时后局部刷新 #main>*。
 * #main 内涵盖工具栏（含 mirrorToolbar）、代码列表 dtable、commit 列表 sidebar；
 * #main 外的一级导航 #navbar 与二级导航 #pageToolbar 保持不变，避免无意义重渲。
 */
window.mirrorSyncDelayedReload = function()
{
    setTimeout(function()
    {
        if(typeof loadPage === 'function') return loadPage({selector: '#main>*'});
        window.location.reload();
    }, window.MIRROR_SYNC_RELOAD_DELAY);
};

/* 刷新同步状态：GET 进度，running → toast 仍在同步；其他状态 → reload 让首屏重渲。 */
$(document).on('click', '.refresh-sync-btn', function()
{
    var $btn = $(this);
    if($btn.prop('disabled')) return;
    mirrorBusy($btn, true);

    $.get(mirrorSyncProgressLink).done(function(res)
    {
        var data = mirrorParse(res);
        if(!data || data.result !== 'success')
        {
            zui.Messager.show({type: 'danger', content: (data && data.message) || mirrorLang.queryFailed, time: 2000});
            return mirrorBusy($btn, false);
        }
        if(data.status && data.status !== 'running')
        {
            zui.Messager.show({type: 'success', content: mirrorLang.statusUpdated, time: 1200});
            return mirrorReload();
        }
        zui.Messager.show({type: 'info', content: mirrorLang.stillRunning, time: 1500});
        mirrorBusy($btn, false);
    }).fail(function(_, textStatus, errorThrown)
    {
        zui.Messager.show({type: 'danger', content: mirrorLang.queryRequestFailed + ': ' + textStatus + (errorThrown ? ' / ' + errorThrown : ''), time: 3000});
        mirrorBusy($btn, false);
    });
});

/* 查看详情：先拉最新进度，failed 才弹原因，其他状态按状态码 toast + reload 与首屏对齐。 */
$(document).on('click', '.sync-failure-detail', function(e)
{
    e.preventDefault();
    var $self  = $(this);
    var $alert = $self.closest('.sync-failure-alert');
    if($self.prop('disabled')) return;
    $self.prop('disabled', true);

    $.get(mirrorSyncProgressLink).done(function(res)
    {
        var data = mirrorParse(res);
        if(!data || data.result !== 'success')
        {
            zui.Messager.show({type: 'danger', content: (data && data.message) || mirrorLang.queryFailed, time: 2000});
            return $self.prop('disabled', false);
        }
        if(data.status === 'failed')
        {
            zui.Modal.alert({title: mirrorLang.failureTitle, message: data.failure || $alert.attr('data-failure') || mirrorLang.noDetail});
            return $self.prop('disabled', false);
        }

        var tipMap  = {running: mirrorLang.syncing, scheduled: mirrorLang.done, finished: mirrorLang.done};
        var typeMap = {running: 'info', scheduled: 'success', finished: 'success'};
        zui.Messager.show({type: typeMap[data.status] || 'info', content: tipMap[data.status] || mirrorLang.statusUpdated, time: 1200});
        mirrorReload();
    }).fail(function(_, textStatus, errorThrown)
    {
        zui.Messager.show({type: 'danger', content: mirrorLang.queryRequestFailed + ': ' + textStatus + (errorThrown ? ' / ' + errorThrown : ''), time: 3000});
        $self.prop('disabled', false);
    });
});
