/**
 * 添加团队成员后重新渲染模块选择器部件。
 * Re-render taskAssignedTo widget after adding member.
 *
 * @param  int    objectID
 * @access public
 * @return void
 */
renderTaskAssignedTo = function(objectID)
{
    if(config.debug) console.log('[ZIN] Rendering task assigned to');

    const getAssignedToLink = $.createLink('execution', 'ajaxGetMembers', 'objectID=' + objectID);
    const taskMode          = $('[name=mode]').val();
    $.getJSON(getAssignedToLink, function(data)
    {
        $('.taskAssignedToBox input.pick-value, .taskAssignedToBox select.pick-value').each(function()
        {
            if(taskMode == 'multi' && $(this).attr('name') == 'assignedTo') return;

            let oldAssignedTo     = $(this).val();
            let $assignedToPicker = $(this).zui('picker');
            $assignedToPicker.render({items: data});
            $assignedToPicker.$.setValue(oldAssignedTo);
        });
    });
}
