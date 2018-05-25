function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}

$(document).ready(function()
{
  var num = $("#mainActions .btn-toolbar a").size();
  if(num == 0)
  {
    $("#mainActions .btn-toolbar").hide();
  }
});
