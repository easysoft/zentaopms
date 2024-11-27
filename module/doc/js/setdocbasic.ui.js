window.loadExecutions = function(e)
{
    const officeTypes      = $('#setDocBasicForm').data('officeTypes');
    const docType          = $('#setDocBasicForm').data('docType');
    const isOfficeType     = officeTypes.includes(docType);
    const projectElement   = isOfficeType ? '.projectBox input[name="project"]': '#setDocBasicForm input[name="project"]';
    const executionElement = isOfficeType ? '.executionBox input[name="execution"]': '#setDocBasicForm input[name="execution"]';
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
        const $libPicker = $(`${isOfficeType ? '' : '#setDocBasicForm'} [name='lib']`).zui('picker');
        $libPicker.render({items: data.libs});
        $libPicker.$.setValue('');

        const $modulePicker = $(`${isOfficeType ? '' : '#setDocBasicForm'} [name='module']`).zui('picker');
        $modulePicker.render({items: data.modules});
        $modulePicker.$.setValue('');
    });
}
