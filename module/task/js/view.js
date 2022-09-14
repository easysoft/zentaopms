$(function()
{
    if(config.onlybody == 'yes') $('.main-actions').css('width', '100%');
});

function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}

$(document).ready(function()
{
    limitIframeLevel();
    /* Ajax refresh view page when close effort modal. */
    $(document).on('mousedown', '#triggerModal .modal-header button.close', function()
    {
        var href = $(this).closest('#triggerModal').attr('ref').toLowerCase();
        if(href.indexOf('recordestimate') < 0 && href.indexOf('createforobject') < 0) return;

        $.get(location.href, function(data)
        {
            $data = $(data);
            $('#actionbox ol.histories-list').html($data.find('#actionbox ol.histories-list').html());
            $('.side-col').html($data.find('.side-col').html());

            if($('#actionbox ol.histories-list #lastComment').length > 0) $('#actionbox ol.histories-list #lastComment').kindeditor();
        });
    })
});
