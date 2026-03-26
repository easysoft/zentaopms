window.insertToDoc = function(blockID, insertLink)
{
    const docID     = getDocApp()?.docID;
    const blockType = 'gantt';

    const url = $.createLink('doc', 'buildZentaoList', `docID=${docID}&type=${blockType}&blockID=${blockID}`);
    const formData = new FormData();
    formData.append('ganttOptions', JSON.stringify(ganttOptions));
    formData.append('ganttFields', JSON.stringify(ganttFields));
    formData.append('showFields',  JSON.stringify(showFields));
    formData.append('url', insertLink);

    $.post(url, formData, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success')
        {
            const oldBlockID = resp.oldBlockID;
            const newBlockID = resp.newBlockID;
            zui.Modal.hide();
            window.insertZentaoList && window.insertZentaoList(blockType, newBlockID, null, oldBlockID);
        }
    });
}

window.setVersionDropdownHeader = function()
{
    return {
        component: 'Listitem',
        className: 'not-hide-menu',
        props: {
            text: versionLangData.allVersions,
            titleClass: 'text-gray',
            actions: [
                {icon: 'exchange', text: versionLangData.compare, className: this.state.showCheckbox ? 'invisible pointer-events-none' : 'text-primary', onClick: () => {
                    diffMode = true;
                    this.setState({showCheckbox: true});
                    $(this.base).find('li.menu-item .item-actions').addClass('hidden');
                    $('#versionBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                    $('#compareBox').removeClass('hidden');
                    $('#nextBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                }},
            ],
        },
    };
};

window.setVersionDropdownFooter = function()
{
    if (!this.state.showCheckbox) return null;
    return {
        component: 'Toolbar',
        props: {
            gap: 4,
            className: 'p-1 pt-0',
            items: [
                {text: versionLangData.confirm, size: 'sm', disabled: this.getChecks().length < 2, type: 'primary', onClick: () =>
                {
                    this.setState({showCheckbox: false})
                    prevVersion = $('#versionBox').attr('data-value');
                    nextVersion = $('#nextBox').attr('data-value');
                    postAndLoadPage(browseTemplate.replace('%s', prevVersion), "baselineVersion=" + nextVersion);
                }},
                {text: versionLangData.cancel, size: 'sm', className: 'not-hide-menu', type: 'default', onClick: (e) =>
                {
                    diffMode = false;
                    this.setState({showCheckbox: false})
                    $(this.base).find('li.menu-item .item-actions').removeClass('hidden');
                    $('#versionBox').attr('title', currentVersion).html('<span class="text">' + currentVersion + '</span><span class="caret"></span>').removeAttr('data-value');
                    $('#compareBox').addClass('hidden');
                    $('#nextBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                    e.stopPropagation();
                }},
            ],
        },
    };
};

window.getVersionItem = function(item)
{
    if(!this.state.showCheckbox) return item;

    const hasPrevVersion = typeof $('#versionBox').data('value') != 'undefined';
    const hasNextVersion = typeof $('#nextBox').data('value') != 'undefined';
    if(!hasPrevVersion && !hasNextVersion) this.state.checked = {};
    if(hasPrevVersion && item.value == $('#versionBox').data('value')) this.state.checked[item.key] = true;
    if(hasNextVersion && item.value == $('#nextBox').data('value'))    this.state.checked[item.key] = true;
    item = $.extend({checked: !!this.state.checked[item.key]}, item);

    if (!item.checked && item.disabled === undefined) item = $.extend({disabled: this.getChecks().length >= 2}, item);
    return item;
};

window.setClickVersionItem = function(info)
{
    if(this.state.showCheckbox)
    {
        const prevVersion = $('#versionBox').attr('data-value');
        const nextVersion = $('#nextBox').attr('data-value');
        if(prevVersion == undefined && (nextVersion == undefined || nextVersion != info.item.value))
        {
            $('#versionBox').attr('data-value', info.item.value).attr('title', info.item.title).html('<span class="text">' + info.item.title + '</span><span class="caret"></span>');
        }
        else if(prevVersion == info.item.value)
        {
            $('#versionBox').removeAttr('data-value').removeAttr('title').html('<span class="caret"></span>');
        }
        else if(nextVersion == undefined && (prevVersion == undefined || prevVersion != info.item.value))
        {
            $('#nextBox').attr('data-value', info.item.value).attr('title', info.item.title).html('<span class="text">' + info.item.title + '</span><span class="caret"></span>');
        }
        else if(nextVersion == info.item.value)
        {
            $('#nextBox').removeAttr('data-value').removeAttr('title').html('<span class="caret"></span>');
        }

        info.event.stopPropagation();
    }
    else
    {
        loadPage(browseTemplate.replace('%s', info.item.value));
    }
};

window.exchangeVersion = function(e)
{
    const prevVersion = $('#versionBox').attr('data-value');
    const nextVersion = $('#nextBox').attr('data-value');
    if(!prevVersion || !nextVersion) return;

    const $dropdown = $('#versionBox').zui('dropdown');
    const $menu     = $dropdown.menu;
    if($menu.state.showCheckbox) $menu.setState({showCheckbox: false})
    if(!$dropdown.shown) e.stopPropagation();

    postAndLoadPage(browseTemplate.replace('%s', nextVersion), "baselineVersion=" + prevVersion);
};

window.showMenu = function()
{
    if(!diffMode) return;
    this.menu.setState({showCheckbox: true});
    $(this.menu.base).find("li.menu-item .item-actions").addClass("hidden");
};