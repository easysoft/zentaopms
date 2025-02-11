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

    let $elements = $('.taskAssignedToBox input.pick-value, .taskAssignedToBox select.pick-value');
    if(config.rawMethod == 'batchedit') $elements = $(`.taskAssignedToBox[data-object='${objectID}'] input.pick-value`);

    const getAssignedToLink = $.createLink('execution', 'ajaxGetMembers', 'objectID=' + objectID);
    $.getJSON(getAssignedToLink, function(data)
    {
        $elements.each(function()
        {
            let oldAssignedTo     = $(this).val();
            let $assignedToPicker = $(this).zui('picker');
            $assignedToPicker.render({items: data});
            $assignedToPicker.$.setValue(oldAssignedTo);
        });
    });
}
