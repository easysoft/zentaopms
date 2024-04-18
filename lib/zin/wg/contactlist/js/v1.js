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
        $('#contactBox').addClass('p-0 w-24');
        $('#contactBox #manageContact').addClass('hidden');
        $('#contactBox .picker-box').removeClass('hidden').zui('picker').render({items: contacts});
    });
}

/**
 * 选择联系人列表后加载联系人。
 * Load contact users after selecting contact list.
 *
 * @param  string value
 * @access public
 * @return void
 */
window.loadContactUsers = function(value)
{
    const event   = this.base;
    const $picker = $(event).zui('picker');
    if($picker === undefined)
    {
        if(config.debug) console.log('[ZIN] Contact picker not found');
        return;
    }

    if(!value) return;

    const link   = $.createLink('user', 'ajaxGetContactUsers', 'listID=' + value);
    const target = this.props.loadTarget === undefined ? '' : this.props.loadTarget;
    $.getJSON(link, function(users)
    {
        const $target = target ? $("[name^='" + target + "']") : $(event).closest('span').prev();
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
