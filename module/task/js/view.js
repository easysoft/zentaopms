function assign(taskID, assignedTo)
{
  $('.assign').width(150);
  $('.assign').height(40);
  $('.assign').load(createLink('user', 'ajaxGetUser', 'taskID=' + taskID + '&assignedTo=' + assignedTo));
}
$(function()
{
    if(onlybody != 'yes') $('.iframe').colorbox({width:900, height:500, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
})
