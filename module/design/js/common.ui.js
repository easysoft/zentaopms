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

/**
 * 渲染需求下拉框中的选项。
 * Options in the render story drop-down box.
 *
 * @access public
 * @return void
 */
window.loadStory = function()
{
    const productID = $(this).val();
    const link      = $.createLink('design', 'ajaxGetProductStories', 'productID=' + productID + '&projectID=' + projectID + '&status=active&hasParent=false');
    $.get(link, function(data)
    {
        $('#story').replaceWith(data);
    })
}
