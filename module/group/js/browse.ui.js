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
    if(window.confirm(confirmDelete))
    {
        $.ajaxSubmit({url: $.createLink('group', 'delete', 'groupID=' + groupID)});
    }
}
