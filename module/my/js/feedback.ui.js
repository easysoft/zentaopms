$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('feedbackIDList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

window.clickTotask = function(event)
{
    const params = $(event.target).closest('a').attr('href').split('&');
    $('#feedbackID').val(params[0]);
    getProjects(params[1]);
    changeTaskProjects();
};

window.toTask = function()
{
    const projectID   = $('[name="taskProjects"]').val();
    const executionID = $('[name="executions"]').val() ? $('[name="executions"]').val() : 0;
    const feedbackID  = $('#feedbackID').val();
    changeTaskProjects();

    if(projectID && executionID != 0)
    {
        zui.Modal.hide('#toTask');

        const url = $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + ',feedbackID=' + feedbackID);
        openPage(url, 'execution');
    }
    else if(projectID == 0)
    {
        zui.Modal.alert(errorNoProject);
    }
    else
    {
        zui.Modal.alert(errorNoExecution);
    }
};

function getProjects(productID)
{
    const link = $.createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + '&field=taskProjects');
    $.getJSON(link, function(data)
    {
        if(data)
        {
            let $projectPicker = $('[name=taskProjects]').zui('picker');
            $projectPicker.render(data);
            $projectPicker.$.setValue('');
        }
    });
}

function changeTaskProjects(event)
{
    const projectID = event != undefined ?  $(event.target).val() : $('[name="taskProjects"]').val();
    const link      = $.createLink('feedback', 'ajaxGetExecutions', 'projectID=' + projectID);
    $.getJSON(link, function(data)
    {
        if(data)
        {
            let $executionPicker = $('[name=executions]').zui('picker');
            $executionPicker.render(data);
            $executionPicker.$.setValue('');
        }
    });
}
