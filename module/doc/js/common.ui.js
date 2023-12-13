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
            $.getJSON(link, function(users)
            {
                if(users != 'private' && users)
                {
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
    $.getJSON(groupLink, function(groups)
    {
        if(groups != 'private' && groups)
        {
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
window.locateNewLib = function(type, objectID, libID)
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

window.rendDocCell = function(result, {col, row})
{
    if(col.name == 'title')
    {
        let docNameHtml = `<div data-status='${row.data.status}' class='flex w-full doc-title'>`;
        const docType   = iconList[row.data.type];
        const docIcon   = `<img src='static/svg/${docType}.svg' class='file-icon mr-1'>`;
        if(canViewDoc)
        {
            docNameHtml += `<a class="doc-name flex" href="` + $.createLink('doc', 'view', 'docID=' + row.data.id) + '">' + docIcon + ' ' + row.data.title + '</a>';
        }
        else
        {
            docNameHtml += `<span class='doc-name flex'>${docIcon} ${row.data.title}</span>`;
        }

        if(row.data.status == 'draft') docNameHtml += `<span class="label special-pale rounded-full ml-1">${draftText}</span>`;
        if(canCollect)
        {
            const starIcon = row.data.collector.indexOf(',' + currentAccount + ',') >= 0 ? 'star' : 'star-empty';

            docNameHtml += `<a class='ajaxCollect ajax-submit' href="` + $.createLink('doc', 'collect', `objectID=${row.data.id}&objectType=doc`) + `"><img src='static/svg/${starIcon}.svg' class='${starIcon} ml-1'></a>`;
        }
        docNameHtml +='</div>';
        result[0] = {html: docNameHtml};
        return result;
    }

    if(col.name == 'module')
    {
        const moduleDivide = row.data.moduleName ? ' > ' : '';
        const moduleName   = row.data.libName + moduleDivide + row.data.moduleName;
        const spaceMethod  = spaceMethodList[row.data.objectType];

        let moduleHtml = '';
        if(spaceMethod && eval(`${spaceMethod}Priv`))
        {
            let spaceParams = `libID=${row.data.lib}&moduleID=${row.data.module}`;
            if(['product', 'project', 'execution', 'custom'].indexOf(row.data.objectType) !== -1) spaceParams = `objectID=${row.data.objectID}&${spaceParams}`;
            if(row.data.objectType == 'mine') spaceParams = `type=${row.data.objectType}&${spaceParams}`;

            moduleHtml = `<a data-app='${currentTab}' href="` + $.createLink('doc', spaceMethod, spaceParams) + '">' + moduleName + '</a>';
        }
        else
        {
            moduleHtml = `<span>${moduleName}</span>`;
        }

        result[0] = {html: moduleHtml};
        result[1] = {attrs: {title: moduleName}};

        return result;
    }
    if(col.name == 'actions')
    {
        if(col.setting.list.edit && row.data.type != 'text')
        {
            result[0]['props']['items'][0]['data-toggle'] = 'modal';
            return result;
        }
    }
    return result;
}

window.loadObjectModules = function(e)
{
    const objectID   = e.target.value;
    const objectType = e.target.name;

    if(objectType == 'execution' && objectID == 0)
    {
        objectType = 'project';
        objectID   = $('[name=project]').val();
    }

    if(!objectID || !objectType) return false;

    let docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    if(typeof docType == 'undefined') docType = 'doc';
    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=' + objectType + '&objectID=' + objectID + '&type=' + docType);

    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $picker = $("[name='module']").zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');
    });
}

window.toggleWhiteList = function(e)
{
    const acl = e.target.value;
    $('#whitelistBox').toggleClass('hidden', acl == 'open');
}

$(document).on('mousedown', '.ajaxCollect', function (event)
{
    if(event.button != 0) return;

    var obj = $(this);
    var url = obj.data('link');
    $.get(url, function(response)
    {
        if(response.status == 'yes')
        {
            obj.children('img').attr('src', 'static/svg/star.svg');
            obj.parent().prev().children('.file-name').children('i').remove('.icon');
            obj.parent().prev().children('.file-name').prepend('<i class="icon icon-star text-yellow"></i> ');
        }
        else
        {
            obj.children('img').attr('src', 'static/svg/star-empty.svg');
            obj.parent().prev().children('.file-name').children('i').remove(".icon");
        }
    }, 'json');
    return false;
});
