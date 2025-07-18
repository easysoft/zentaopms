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

window.changeIsDeliverable = function()
{
    if($(this).val() == '1')
    {
        $('[type=radio][name=acl]').attr('disabled', 'disabled');
        $('#acl').removeAttr('disabled');
        $("[type=radio][name='acl'][value='open']").prop('checked', true);
        $('[type=radio][name=acl]').closest('.radio-primary').addClass('disabled');
        $('[type=radio][name=acl]').closest('.check-list').addClass('disabled');
    }
    else
    {
        $('#acl').attr('disabled', 'disabled');
        $('[type=radio][name=acl]').removeAttr('disabled');
        $('[type=radio][name=acl]').closest('.radio-primary').removeClass('disabled');
        $('[type=radio][name=acl]').closest('.check-list').removeClass('disabled');
    }
}
