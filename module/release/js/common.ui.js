/**
 * 提示并删除发布。
 * Delete release with tips.
 *
 * @param  int    releaseID
 * @access public
 * @return void
 */
window.confirmDelete = function(releaseID)
{
    zui.Modal.confirm(confirmDelete).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('release', 'delete', 'releaseID=' + releaseID)});
    })
}

/**
 * 激活/停止维护发布。
 * Change release status.
 *
 * @param  int    releaseID
 * @param  string changeStatus
 * @access public
 * @return void
 */
window.changeStatus = function(releaseID, changeStatus)
{
    $.ajaxSubmit({
        url: $.createLink('release', 'changeStatus', `releaseID=${releaseID}&status=${changeStatus}`)
    });
}
