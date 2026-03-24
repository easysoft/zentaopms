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
                    this.setState({showCheckbox: true});
                    $(this.base).find('li.menu-item .item-actions').addClass('hidden');
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
                    this.setState({showCheckbox: false})
                    $(this.base).find('li.menu-item .item-actions').removeClass('hidden');
                    e.stopPropagation();
                }},
            ],
        },
    };
};

window.getVersionItem = function(item)
{
    if (!this.state.showCheckbox) return item;

    item = $.extend({checked: !!this.state.checked[item.key]}, item);
    if (!item.checked && item.disabled === undefined) item = $.extend({disabled: this.getChecks().length >= 2}, item);
    return item;
};

window.setClickVersionItem = function(info)
{
    if (this.state.showCheckbox)
    {
        info.event.stopPropagation();
    }
    else
    {
        loadPage(browseTemplate.replace('%s', info.item.value));
    }
};