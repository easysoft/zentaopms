$(function()
{
    if(typeof type != 'undefined') $('#mainNavbar .nav a[data-id=' + type + ']').addClass('active');
});


/**
 * 渲染需求下拉框中的选项。
 * Options in the render story drop-down box.
 *
 * @access public
 * @return void
 */
function loadStory(e)
{
    const productID = $(e.target).val();
    const storyID   = $('input[name=story]').val();
    const link      = $.createLink('design', 'ajaxGetProductStories', 'productID=' + productID + '&projectID=' + projectID + '&status=active&hasParent=false');
    $.getJSON(link, function(data)
    {
        const $storyPicker = $('input[name=story]').zui('picker');
        $storyPicker.render({items: data});
        $storyPicker.$.setValue(storyID);
    })
}
