/**
 * 提示并删除阶段。
 * Delete stage with tips.
 *
 * @param  int    stageID
 * @access public
 * @return void
 */
window.confirmDelete = function(stageID)
{
    zui.Modal.confirm(confirmDelete).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('stage', 'delete', 'stageID=' + stageID)});
    })
}
