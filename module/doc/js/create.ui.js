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

    $('.form').on('click', '#saveDraft', function()
    {
        if($('#title').val() == '') 
        {   
            zui.Modal.alert(titleNotEmpty);
            return false;
        }

        $('[name=status]').val('draft');
    });
})

window.toggleWhiteList = function(e)
{
    const acl = e.target.value;
    $('#whitelistBox').toggleClass('hidden', acl == 'open');
}

window.loadExecutions = function(e)
{
    const projectID = e.target.value;
    const link = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&executionID=0&mode=multiple,leaf,noprefix&type=sprint,stage");
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $picker = $("[name='execution']").zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}
