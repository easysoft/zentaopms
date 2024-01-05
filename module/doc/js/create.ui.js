$(function()
{
    $('.form').on('click', '#basicInfoLink', function()
    {
        if($('#title').val() == '')
        {
            zui.Modal.alert(titleNotEmpty);
            return false;
        }

        if(requiredFields.indexOf('content') >= 0)
        {
            if($('[name="content"]').val() == '')
            {
                zui.Modal.alert(contentNotEmpty);
                return false;
            }
        }

        $('#status').val('normal');
    });
})

window.loadExecutions = function(e)
{
    const projectID   = e.target.value;
    const executionID = $('#execution').val();
    if(executionID)
    {
        const link = createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&executionID=" + executionID + "&mode=multiple,leaf,noprefix&type=sprint,stage");
    }
    else
    {
        const link = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix&type=sprint,stage");
    }
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $picker = $("[name='execution']").zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}

window.clickSubmit = function(e)
{
    if($(e.submitter).hasClass('save-draft')) $('input[name=status]').val('draft');
}
