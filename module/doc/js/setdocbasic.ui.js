window.loadExecutions = function()
{
    const $project   = $('#setDocBasicForm input[name="project"]');
    const $execution = $('#setDocBasicForm input[name="execution"]');
    const projectID  = $project.val();

    if($execution.length)
    {
        const executionID = $execution.val();
        const link        = $.createLink('project', 'ajaxGetExecutions', `projectID=${projectID}&mode=multiple,leaf,noprefix`);
        $.getJSON(link, function(data)
        {
            const picker = $(executionElement).zui('picker');
            picker.render({items: data.items, disabled: !data.multiple});
            picker.$.setValue(executionID);
        });
    }

    const link = $.createLink('doc', 'ajaxGetModules', `objectType=project&objectID=${projectID}&type=doc`);
    $.getJSON(link, function(data)
    {
        const libPicker = $(`#setDocBasicForm [name="lib"]`).zui('picker');
        libPicker.render({items: data.libs});
        libPicker.$.setValue('');

        const modulePicker = $(`#setDocBasicForm [name="module"]`).zui('picker');
        modulePicker.render({items: data.modules});
        modulePicker.$.setValue('');
    });
}
