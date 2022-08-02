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
});
