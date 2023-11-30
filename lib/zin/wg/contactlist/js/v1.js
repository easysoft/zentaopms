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
 * @param  string target
 * @param  object ele
 * @access public
 * @return void
 */
function loadContactUsers(target, ele)
{
    const $picker = $(ele).zui('picker');
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
        let $targetPicker = $('[name^="' + target + '"]').zui('picker');
        let targetUsers   = $('[name^="' + target + '"]').val();
        users.forEach(user => {targetUsers.push(user.value);});
        $targetPicker.$.setValue(targetUsers);
    });
}
