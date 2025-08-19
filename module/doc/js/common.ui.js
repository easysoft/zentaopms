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
        if(libType != 'mine')
        {
            $('#whiteListBox').removeClass('hidden');
            $('#groupBox').removeClass('hidden');
        }
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
        $('#groupBox').addClass('hidden');
    }
    $('#whiteListBox .notice').remove();

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
        $('#whiteListBox, #readListBox').toggleClass('hidden', acl == 'open');
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
    const dtable = zui.DTable.query('#docTable');
    if(!dtable) return;
    if(col.name == 'title' && result[0])
    {
        const doc      = row.data;
        const starIcon = doc.collector.indexOf(',' + dtable.options.currentAccount + ',') >= 0 ? 'star' : 'star-empty';
        const docType  = dtable.options.iconList[doc.type];
        const docIcon  = doc.type == 'text' ? 'wiki-file' : doc.type;
        let html = "<img src='static/svg/" + docIcon + ".svg' class='file-icon'/>";
        result.unshift({html});
        if(doc.status == 'draft')
        {
            html = "<span class='label special-pale rounded-full draft'>" + dtable.options.draftText + '</span>';
            result.push({html});
        }
        if(dtable.options.canCollect)
        {
            html = "<a href='" + $.createLink('doc', 'collect', 'objectID=' + doc.id + '&objectType=doc') + "' class='btn btn-link ajax-submit star'><img src='static/svg/" + starIcon + ".svg'/></a>";
            result.push({html});
        }
        if(result[1]['props']) result[1]['props']['class'] = 'text-ellipsis';
        return result;
    }

    if(col.name == 'module')
    {
        const moduleDivide = row.data.moduleName ? ' > ' : '';
        const moduleName   = row.data.libName + moduleDivide + row.data.moduleName;
        const spaceMethod  = typeof spaceMethodList != 'undefined' ? spaceMethodList[row.data.objectType] : '';

        let moduleHtml = '';
        if(spaceMethod && eval(`${spaceMethod}Priv`))
        {
            let spaceParams = `libID=${row.data.lib}&moduleID=${row.data.module}`;
            if(['product', 'project', 'execution', 'custom'].indexOf(row.data.objectType) !== -1) spaceParams = `objectID=${row.data.objectID}&${spaceParams}`;
            if(row.data.objectType == 'mine') spaceParams = `type=${row.data.objectType}&${spaceParams}`;

            moduleHtml = `<a data-app='${dtable.options.currentTab}' href="` + $.createLink('doc', spaceMethod, spaceParams) + '">' + moduleName + '</a>';
        }
        else
        {
            moduleHtml = `<span>${moduleName}</span>`;
        }

        result[0] = {html: moduleHtml};
        result[1] = {attrs: {title: moduleName}};

        return result;
    }
    if(col.name == 'actions' && result[0].length)
    {
        if(col.setting.list.edit && row.data.type != 'text')
        {
            result[0]['props']['items'][0]['data-toggle'] = 'modal';
            return result;
        }
    }
    return result;
}

window.loadObjectModules = function(e, docID)
{
    let objectID   = e.target.value;
    let objectType = e.target.name;

    if(objectType == 'space')
    {
        let targetSpace = objectID.split('.');
        objectType = targetSpace[0];
        objectID   = targetSpace[1];
    }

    if(objectType == 'execution' && objectID == 0)
    {
        objectType = 'project';
        objectID   = $('[name=project]').val();
    }

    if(!objectType || (objectType != 'mine' && !objectID)) return false;

    let docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    if(typeof docType == 'undefined') docType = 'doc';

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=' + objectType + '&objectID=' + objectID + '&type=' + docType + '&docID=' + docID);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $libPicker = $("[name='lib']").zui('picker');
        $libPicker.render({items: data.libs});
        $libPicker.$.setValue('');

        const $modulePicker = $("[name='parent']").zui('picker');
        $modulePicker.render({items: data.modules});
        $modulePicker.$.setValue('');
    });
}

window.loadLibModules = function(e, docID)
{
    const libID    = e.target.value;
    const libType  = $(e.target.closest('div.form-group')).attr('data-lib-type');
    const objectID = $("input[name='" + libType + "']").val();

    if(typeof docType == 'undefined')
    {
        docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
        if(typeof docType == 'undefined') docType = 'doc';
    }

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=' + libType + '&objectID=' + objectID + '&type=' + docType + '&docID=' + docID + '&libID=' + libID);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        if(docType == 'docTemplate')
        {
            data = data.filter(function(item)
            {
                return item.value != 0;
            });
        }

        const $modulePicker = $("[name='parent']").zui('picker');
        $modulePicker.render({items: data.modules});
        $modulePicker.$.setValue('');
    });
}

window.loadScopeTypes = function(e)
{
    const libID = e.target.value;
    const link  = $.createLink('doc', 'ajaxGetScopeTypes', 'libID=' + libID);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $modulePicker = $("[name='module']").zui('picker');
        $modulePicker.render({items: data});
        $modulePicker.$.setValue('');
    });
}

window.toggleWhiteList = function(e)
{
    const acl = e.target.value;
    $('#whiteListBox').toggleClass('hidden', acl == 'open');
    $('#readListBox').toggleClass('hidden', acl == 'open');
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

window.updateOrder = function(event, orders)
{
    const type = $(event.item).children('div').attr('data-type');

    let sortedIdList = {};
    for(let i in orders)
    {
        if(i != 'annex') sortedIdList['orders[' + orders[i] + ']'] = i;
    }

    sortedIdList['type'] = 'doc';

    if(type == 'module')
    {
        $.post($.createLink('doc', 'sortCatalog'), sortedIdList);
    }
    else if(type == 'docLib')
    {
        $.post($.createLink('doc', 'sortDoclib'), sortedIdList);
    }
}

window.canSortTo = function(e, from, to)
{
    if(from['data-objectType'] != to['data-objectType']) return false;
    if(to['data-type'] == 'annex') return false;
}

window.checkObjectPriv = function(e)
{
    $whiteListBox = $('#whiteListBox');
    if($whiteListBox.length == 0 || $whiteListBox.hasClass('hidden')) return;

    let $users = $('#whiteListBox [name^=users]');
    let users  = $users.val();
    if(users.length == 0) return;

    let formData   = new FormData();
    let $object    = $('[name=' + libType + ']');
    let objectType = libType;
    let objectID   = 0;
    if($object.length > 0) objectID = $object.val();
    if(libType == 'project')
    {
        let $execution = $('[name=execution]');
        if($execution.length > 0 && parseInt($execution.val()) > 0)
        {
            objectType = 'execution';
            objectID   = $execution.val();
        }
    }
    if(objectID == 0) return;

    users.forEach(function(user){ formData.append('users[]', user); });
    $.post($.createLink('doc', 'ajaxCheckObjectPriv', 'libType=' + libType + '&objectID=' + objectID), formData, function(data)
    {
        $inputGroupBox = $users.closest('.input-group').parent();
        $inputGroupBox.find('.notice').remove();

        if(!data) return;
        $inputGroupBox.append("<div class='notice pt-1'>" + data + '</div>');
    });
}

window.checkLibPriv = function(id, formName)
{
    $listBox = $(id);
    if($listBox.length == 0 || $listBox.hasClass('hidden')) return;

    let $users = $listBox.find(`[name^=${formName}]`);
    let users  = $users.val();
    if(users.length == 0) return;

    let formData = new FormData();
    let libID    = $('[name=lib]').val();

    users.forEach(function(user){ formData.append('users[]', user); });
    $.post($.createLink('doc', 'ajaxCheckLibPriv', 'libID=' + libID), formData, function(data)
    {
        $inputGroupBox = $users.closest('.input-group').parent();
        $inputGroupBox.find('.notice').remove();

        if(!data) return;
        $inputGroupBox.append("<div class='notice pt-1'>" + data + '</div>');
    });
}
