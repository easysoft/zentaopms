/**
 * 提示并删除分组。
 * Delete group with tips.
 *
 * @param  int    groupID
 * @access public
 * @return void
 */
window.confirmDelete = function(groupID)
{
    zui.Modal.confirm({message: confirmDelete, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('group', 'delete', 'groupID=' + groupID)});
    });
}
