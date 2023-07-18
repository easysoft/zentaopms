window.loadExecution = function()
{
    const projectID = $('input[name=project]').val();
    $.get($.createLink('doc', 'ajaxGetExecution', 'projectID=' + projectID), function(data)
    {
        data = JSON.parse(data);
        if(data.items)
        {
            const $executionsPicker = $('input[name=execution]').zui('picker');
            $executionsPicker.render({items: data.items, disabled: !data.project.multiple});
            $executionsPicker.$.setValue('');
        }
    });
}

window.changeDoclibAcl = function(e)
{
    const libType = $(e.target).val();
    if(libType == 'api')
    {
        $('.apilib').removeClass('hidden');
        $('#aclBox .check-list').html($('#aclAPIBox .check-list').html());
        $("#aclBox input[id='acl_open']").prop('checked', true);

        $('.executionBox').addClass('hidden');
    }
    else
    {
        $('.apilib').addClass('hidden');
        $('#aclBox .check-list').html($('#aclOtherBox .check-list').html());
        $("#aclBox input[id='acl_default']").prop('checked', true);

        $('.executionBox').removeClass('hidden');
    }
    $('#whiteListBox').addClass('hidden');
}
