function changeTesttask(event)
{
    const taskID = $(event.target).val();
    const url    = $.createLink('testreport', 'create', 'objectID=' + taskID + '&objectType=testtask');
    loadPage(url);
}

function ignoreRunDate(event)
{
    $(event.target).closest('.form-group').hide();
}
