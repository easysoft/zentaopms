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
        $('span.showTitle').text($('#title').val());

        $('#status').val('normal');
    });
    if($('#modalBasicInfo input[name="project"]').length) loadExecutions();
})

window.loadExecutions = function(e)
{
    const projectElement   = officeTypes.includes(docType) ? '.projectBox input[name="project"]': '#modalBasicInfo input[name="project"]';
    const executionElement = officeTypes.includes(docType) ? '.executionBox input[name="execution"]': '#modalBasicInfo input[name="execution"]';
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
        const moduleElement = officeTypes.includes(docType) ? '.moduleBox input[name="module"]': '#modalBasicInfo input[name="module"]';
        const $picker = $(moduleElement).zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}

window.clickSubmit = function(e)
{
    if($(e.submitter).hasClass('save-draft')) $('input[name=status]').val('draft');
}
