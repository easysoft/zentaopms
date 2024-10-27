window.loadExecutions = function(e)
{
    const officeTypes      = $('#setDocBasicForm').data('officeTypes');
    const docType          = $('#setDocBasicForm').data('docType');
    const projectElement   = officeTypes.includes(docType) ? '.projectBox input[name="project"]': '#setDocBasicForm input[name="project"]';
    const executionElement = officeTypes.includes(docType) ? '.executionBox input[name="execution"]': '#setDocBasicForm input[name="execution"]';
    const projectID        = $(projectElement).val();
    if($(executionElement))
    {
        const executionID = $(executionElement).val();
        const link        = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix");
        $.getJSON(link, function(data)
        {
            let $picker = $(executionElement).zui('picker');
            $picker.render({items: data.items, disabled: !data.multiple});
            $picker.$.setValue(executionID);
        });
    }

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=project&objectID=' + projectID + '&type=doc');
    $.getJSON(link, function(data)
    {
        const moduleElement = officeTypes.includes(docType) ? '.moduleBox input[name="module"]': '#setDocBasicForm input[name="module"]';
        const $picker = $(moduleElement).zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}
