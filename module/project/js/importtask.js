function reload(toProject, fromProject)
{ 
    link = createLink('project','importtask','toProject='+toProject + '&fromProject='+fromProject);
    location.href = link;
}
$(function()
{
    setTimeout(function(){fixedTfootAction('#importTaskForm')}, 500);
});
