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
    if($('#modalBasicInfo input[name="project"]').length) loadExecutions();
})

window.loadExecutions = function(e)
{
    const projectID = $('#modalBasicInfo input[name="project"]').val();
    if($("#modalBasicInfo input[name='execution']"))
    {
        const executionID = $("#modalBasicInfo input[name='execution']").val();
        const link        = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix&type=sprint,stage");
        $.getJSON(link, function(data)
        {
            let $picker = $("#modalBasicInfo input[name='execution']").zui('picker');
            $picker.render({items: data.items, disabled: !data.multiple});
            $picker.$.setValue(executionID);
        });
    }

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=project&objectID=' + projectID + '&type=doc');
    $.getJSON(link, function(data)
    {
        const $picker = $("#modalBasicInfo [name='module']").zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}

window.clickSubmit = function(e)
{
    if($(e.submitter).hasClass('save-draft')) $('input[name=status]').val('draft');
}
