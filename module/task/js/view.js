$(function()
{
    if(config.onlybody == 'yes') $('.main-actions').css('width', '100%');
    limitIframeLevel();
});

function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}

/**
 * Ajax refresh
 *
 * @access public
 * @return void
 */
function ajaxRefresh()
{
    $.get(location.href, function(data)
    {
        $data = $(data);
        $('#actionbox ol.histories-list').html($data.find('#actionbox ol.histories-list').html());
        $('#actionbox ol.histories-list form.comment-edit-form').ajaxForm();
        $('.side-col').html($data.find('.side-col').html());

        if($('#actionbox ol.histories-list #lastComment').length > 0) $('#actionbox ol.histories-list #lastComment').kindeditor();
    });
}
