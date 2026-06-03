window.settingsMode   = false;
window.showVersions   = [];
window.hiddenVersions = [];

/* 判断版本是否在版本菜单中可见。*/
window.isVersionVisible = function(item)
{
    if(showVersions.includes(item.value)) return true;
    if(hiddenVersions.includes(item.value)) return false;
    return item.visible == 1;
};

window.setVersionDropdownHeader = function()
{
    return {
        component: 'Listitem',
        className: 'not-hide-menu',
        props: {
            text: settingsMode ? versionLangData.versionDisplay : versionLangData.allVersions,
            titleClass: 'text-gray',
            actions: canDiffVersion ? [
                {icon: 'backend', text: versionLangData.settings, className: this.state.showCheckbox ? 'invisible pointer-events-none' : 'text-primary', onClick: () => {
                    diffMode       = false;
                    settingsMode   = true;
                    showVersions   = [];
                    hiddenVersions = [];

                    this.setState({showCheckbox: true, items: this?.props?.settingsItems || []});
                    $(this.base).find('li.menu-item .item-actions').addClass('hidden');
                }},
                {icon: 'exchange', text: versionLangData.compare, className: this.state.showCheckbox ? 'invisible pointer-events-none' : 'text-primary', onClick: () => {
                    settingsMode = false;
                    diffMode     = true;
                    this.setState({showCheckbox: true, items: this?.props?.items || []});
                    $(this.base).find('li.menu-item .item-actions').addClass('hidden');
                    $('#versionBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                    $('#compareBox').removeClass('hidden');
                    $('#nextBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                    $('#diffNotice').removeClass('hidden');
                }},
            ] : [],
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
                {text: versionLangData.confirm, size: 'sm', disabled: diffMode ? this.getChecks().length < 2 : false, type: 'primary', onClick: () =>
                {
                    if(settingsMode)
                    {
                        settingsMode = false;

                        this.setState({showCheckbox: false, items: this?.props?.items || []});
                        $.ajaxSubmit({url: $.createLink('programplan', 'ajaxSetShowVersion', 'objectType=' + appTab), data: 'showVersions=' + showVersions + '&hiddenVersions=' + hiddenVersions});
                        return;
                    }

                    this.setState({showCheckbox: false})
                    prevVersion = $('#versionBox').attr('data-value');
                    nextVersion = $('#nextBox').attr('data-value');
                    if(prevVersion == undefined || nextVersion == undefined) return;
                    postAndLoadPage(browseTemplate.replace('%s', prevVersion), "baselineVersion=" + nextVersion);
                }},
                {text: versionLangData.cancel, size: 'sm', className: 'not-hide-menu', type: 'default', onClick: (e) =>
                {
                    if(settingsMode)
                    {
                        settingsMode   = false;
                        showVersions   = [];
                        hiddenVersions = [];

                        this.setState({showCheckbox: false, items: this?.props?.items || []});
                        $(this.base).find('li.menu-item .item-actions').removeClass('hidden');
                        e.stopPropagation();
                        return;
                    }

                    const versionID = $('#versionBox').attr('data-value');
                    const initMode  = $('#versionBox').attr('data-initmode');

                    diffMode = false;
                    this.setState({showCheckbox: false})
                    $(this.base).find('li.menu-item .item-actions').removeClass('hidden');
                    $('#versionBox').attr('title', currentVersion).html('<span class="text">' + currentVersion + '</span><span class="caret"></span>').removeAttr('data-value');
                    $('#compareBox').addClass('hidden');
                    $('#nextBox').html('<span class="caret"></span>').removeAttr('data-value').removeAttr('title');
                    $('#diffNotice').addClass('hidden');
                    if(initMode != 'diff') e.stopPropagation();
                    if(initMode == 'diff') loadPage(browseTemplate.replace('%s', versionID));
                }},
            ],
        },
    };
};

window.getVersionItem = function(item)
{
    if(!this.state.showCheckbox || item.type == 'heading') return item;

    /* 在设置中，默认勾选显示在版本菜单中的版本。*/
    if(settingsMode) return $.extend({checked: isVersionVisible(item)}, item);

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
        if(settingsMode)
        {
            if(isVersionVisible(info.item))
            {
                /* 如果是显示在版本菜单中的版本，当取消勾选时，从showVersions中移除，在hiddenVersions中添加。 */
                showVersions = showVersions.filter((value) => value !== info.item.value);
                if(!hiddenVersions.includes(info.item.value)) hiddenVersions.push(info.item.value);
            }
            else
            {
                /* 如果是不显示在版本菜单中的版本，当勾选时，从hiddenVersions中移除，在showVersions中添加。 */
                hiddenVersions = hiddenVersions.filter((value) => value !== info.item.value);
                if(!showVersions.includes(info.item.value)) showVersions.push(info.item.value);
            }

            info.event.stopPropagation();
            return;
        }

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
    if(prevVersion == undefined || !nextVersion == undefined) return;

    const $dropdown = $('#versionBox').zui('dropdown');
    const $menu     = $dropdown.menu;
    if($menu.state.showCheckbox) $menu.setState({showCheckbox: false, items: $menu?.props?.items || []})
    if(!$dropdown.shown) e.stopPropagation();

    postAndLoadPage(browseTemplate.replace('%s', nextVersion), "baselineVersion=" + prevVersion);
};

window.showMenu = function()
{
    if(settingsMode)
    {
        this.menu.setState({showCheckbox: true, items: this.menu?.props?.settingsItems || []});
        $(this.menu.base).find('li.menu-item .item-actions').addClass('hidden');
        return;
    }

    if(diffMode)
    {
        this.menu.setState({showCheckbox: true, items: this.menu?.props?.items || []});
        $(this.menu.base).find('li.menu-item .item-actions').addClass('hidden');
        return;
    }

    this.menu.setState({items: this.menu?.props?.items || []});
};
