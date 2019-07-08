$(function()
{
    if(objectType == 'project') $('#subNavbar ul li[data-id=qa]').addClass('active');
    $('#mainContent .main-header h2 #selectTask').change(function()
    {
        var taskID = $(this).val();
        if(taskID)
        {
            location.href = createLink('testreport', 'create', 'objectID=' + taskID + '&objectType=testtask');
            return false;
        }
    });
})
