/**
 * Toggle acl.
 *
 * @param  string $acl
 * @param  string $type
 * @access public
 * @return void
 */
function toggleAcl(type)
{
    const acl = $('input[name=acl]:checked').val();
    let libID = $('input[name=lib]').val();
    if($('input[name=lib]').length == 0 && $('input[name=module]').length > 0)
    {
        let moduleID = $('input[name=module]').val();
        if(moduleID.indexOf('_') >= 0) libID = moduleID.substr(0, moduleID.indexOf('_'));
    }
    if(acl == 'private')
    {
        $('#whiteListBox').removeClass('hidden');
        $('#groupBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
        $('#groupBox').addClass('hidden');
    }

    if(type == 'lib')
    {
        if(libType == 'project' && typeof(doclibID) != 'undefined')
        {
            let link = $.createLink('doc', 'ajaxGetWhitelist', 'doclibID=' + doclibID + '&acl=' + acl);
            $.get(link, function(users)
            {
                if(users)
                {
                    users = JSON.parse(users);
                    const $usersPicker = $('select[name^=users]').zui('picker');
                    $usersPicker.render({items: users});
                    $usersPicker.$.setValue('');
                }
            })
        }
    }
    else if(type == 'doc')
    {
        $('#whiteListBox').toggleClass('hidden', acl == 'open');
        $('#groupBox').toggleClass('hidden', acl == 'open');
        loadWhitelist(libID);
    }
}

/**
 * Load whitelist by libID.
 *
 * @param  int    $libID
 * @access public
 * @return void
 */
window.loadWhitelist = function(libID)
{
    let groupLink = $.createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=group');
    let userLink  = $.createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=user');
    $.get(groupLink, function(groups)
    {
        if(groups != 'private' && groups)
        {
            $('#groups').replaceWith(groups);
            $('#groups').next('.picker').remove();
            $('#groups').picker();

            groups = JSON.parse(groups);
            const $groupsPicker = $('select[name^=groups]').zui('picker');
            $groupsPicker.render({items: groups});
            $groupsPicker.$.setValue('');
        }
    });

    $.get(userLink, function(users)
    {
        if(users != 'private' && users)
        {
            users = JSON.parse(users);
            const $usersPicker = $('select[name^=users]').zui('picker');
            $usersPicker.render({items: users});
            $usersPicker.$.setValue('');
        }
    });
}

/**
 * locateNewLib
 *
 * @param  string $type product|project|execution|custom|mine
 * @param  int    $objectID
 * @param  int    $libID
 * @access public
 * @return void
 */
function locateNewLib(type, objectID, libID)
{
    let method = 'teamSpace';
    let params = 'objectID=' + objectID + '&libID=' + libID;
    let module = 'doc';
    if(type == 'product' || type == 'project')
    {
        method = type + 'Space';
    }
    else if(type == 'execution')
    {
        module = 'execution';
        method = 'doc';
    }
    else if(type == 'mine')
    {
        method = 'mySpace';
        params = 'type=mine&libID=' + libID;
    }

    loadPage($.createLink(module, method, params));
}
