/**
 * 提示并删除设计。
 * Delete design with tips.
 *
 * @param  int    designID
 * @access public
 * @return void
 */
window.confirmDelete = function(designID)
{
    if(window.confirm(confirmDelete))
    {
        $.ajaxSubmit({url: $.createLink('design', 'delete', 'designID=' + designID)});
    }
}
