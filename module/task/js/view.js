function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}
$(function(){$('.iframe').colorbox({width:900, height:400, iframe:true, transition:'none'});})
