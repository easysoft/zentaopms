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

let diffMode = false;
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
                    diffMode = true
                    this.setState({showCheckbox: true});
                    $(this.base).find('li.menu-item .item-actions').addClass('hidden');
                    $('#versionBox').html('<span class="caret"></span>').removeAttr('data-value');
                    $('#compareBox').html('<span class="caret"></span>').removeClass('hidden').removeAttr('data-value');
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
                {text: versionLangData.confirm, size: 'sm', disabled: this.getChecks().length < 2, type: 'primary', onClick: () => console.log('点击了确认，已选中对比版本', this.getChecks())},
                {text: versionLangData.cancel, size: 'sm', className: 'not-hide-menu', type: 'default', onClick: (e) => {
                    diffMode = false;
                    this.setState({showCheckbox: false})
                    $(this.base).find('li.menu-item .item-actions').removeClass('hidden');
                    $('#versionBox').html('<span class="text">' + currentVersion + '</span><span class="caret"></span>').removeAttr('data-value');
                    $('#compareBox').html('<span class="caret"></span>').addClass('hidden').removeAttr('data-value');
                    e.stopPropagation();
                }},
            ],
        },
    };
};

window.getVersionItem = function(item)
{
    if(!this.state.showCheckbox) return item;

    item = $.extend({checked: !!this.state.checked[item.key]}, item);
    if (!item.checked && item.disabled === undefined) item = $.extend({disabled: this.getChecks().length >= 2}, item);
    return item;
};

window.setClickVersionItem = function(info)
{
    if(this.state.showCheckbox)
    {
        if(typeof $('#versionBox').attr('data-value') == 'undefined')
        {
            $('#versionBox').attr('data-value', info.item.value).html('<span class="text">' + info.item.title + '</span><span class="caret"></span>');
        }
        else if($('#versionBox').attr('data-value') == info.item.value)
        {
            $('#versionBox').removeAttr('data-value').html('<span class="caret"></span>');
        }
        else if(typeof $('#nextBox').attr('data-value') == 'undefined')
        {
            $('#nextBox').attr('data-value', info.item.value).html('<span class="text">' + info.item.title + '</span><span class="caret"></span>');
        }
        else if($('#nextBox').attr('data-value') == info.item.value)
        {
            $('#nextBox').removeAttr('data-value').html('<span class="caret"></span>');
        }

        info.event.stopPropagation();
    }
    else
    {
        loadPage(browseTemplate.replace('%s', info.item.value));
    }
};