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
        $('#orderBox').addClass('hidden');
        $('#orderBox #orderByid_asc').trigger('click');
        $('#aclBox .check-list').html($('#aclAPIBox .check-list').html());
        $("#aclBox input[id='aclopen']").prop('checked', true);

        $('.executionBox').addClass('hidden');
    }
    else
    {
        $('.apilib').addClass('hidden');
        $('#orderBox').removeClass('hidden');
        $('#aclBox .check-list').html($('#aclOtherBox .check-list').html());
        $("#aclBox input[id='acldefault']").prop('checked', true);

        $('.executionBox').removeClass('hidden');
    }
    $('#whiteListBox').addClass('hidden');
    $('#whiteListBox').find('[name^=users]').zui('picker').$.setValue('');
    $('#whiteListBox .notice').remove();
}

window.toggleNewSpace = function()
{
    const isChecked = $("[name='newSpace']").prop('checked');
    if(isChecked)
    {
        $('#spaceName').removeClass('hidden');
        $('#spaceName').prev('div').addClass('hidden');
    }
    else
    {
        $('#spaceName').addClass('hidden');
        $('#spaceName').prev('div').removeClass('hidden');
    }
}
