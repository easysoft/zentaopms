function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}
function setComment()
{
  $('#comment').show();
}
