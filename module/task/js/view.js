function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}
$(function()
{
    if(onlybody != 'yes') $('.iframe').modalTrigger({width:900, type:'iframe', afterHide:function(){parent.location.href=parent.location.href;}});
})
