/**
 * 添加联系人后重新渲染联系人部件。
 * Render contact list after adding a contact.
 *
 * @access public
 * @return void
 */
renderContactList = function()
{
    if(config.debug) console.log('[ZIN] Rendering contact list');

    const link = $.createLink('user', 'ajaxGetContactList');
    $.getJSON(link, function(contacts)
    {
        $('#contactBox').addClass('p-0 w-24 input-group-addon').removeClass('input-group-btn');
        $('#contactBox #manageContact').addClass('hidden');
        $('#contactBox .picker-box').removeClass('hidden').zui('picker').render({items: contacts});
    });
}

/**
 * 选择联系人列表后加载联系人。
 * Load contact users after selecting contact list.
 *
 * @param  string target
 * @access public
 * @return void
 */
window.loadContactUsers = function(target)
{
    const picker  = event.target;
    const $picker = $(picker).zui('picker');
    if($picker === undefined)
    {
        if(config.debug) console.log('[ZIN] Contact picker not found');
        return;
    }

    const listID = $picker.$.value;
    if(!listID) return;

    const link = $.createLink('user', 'ajaxGetContactUsers', 'listID=' + listID);
    $.getJSON(link, function(users)
    {
        const $target = target ? $("[name^='" + target + "']") : $(picker).closest('#contactBox').prev();
        const $targetPicker = $target.zui('picker');
        if($targetPicker === undefined)
        {
            if(config.debug) console.log('[ZIN] Target picker not found');
            return;
        }

        const targetUsers = $targetPicker.$.valueList.concat(users.map(user => user.value));
        $targetPicker.$.setValue(targetUsers);
    });
}
