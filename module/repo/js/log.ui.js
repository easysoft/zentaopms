var revisionMap = {};
var checkedIds  = [];

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
 * 当选中数量等于2，则禁用其他所有行。
 * When the selected row equals 2, disable all other rows.
 *
 * @param int     rowID
 * @access public
 * @return bool
 */
window.canRowCheckable = function(rowID)
{
    const dtable = zui.DTable.query('#repo-logs-table');
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
    if(revisionMap[data[data.length - 1].id] !== undefined) return;

    revisionMap = {};
    for (var i = 0; i < data.length; i++) revisionMap[data[i].id] = data[i].revision;
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

    openUrl(newDiffLink);
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
    const dtable = zui.DTable.query('#repo-logs-table');
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
