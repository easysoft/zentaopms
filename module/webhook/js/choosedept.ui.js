window.buildTreeItems = function(deptTree, treeItems)
{
    if(typeof(treeItems) == 'undefined') treeItems = [];
    for(i in deptTree)
    {
        let dept     = deptTree[i];
        let treeItem = {key: dept.id, text: dept.name};
        treeItems    = appendItems(treeItems, treeItem, dept.pId);
    }
    return treeItems;
};

window.appendItems = function(treeItems, treeItem, pid)
{
    if(pid == 0)
    {
        treeItems.push(treeItem);
        return treeItems;
    }

    for(i in treeItems)
    {
        hasChild = typeof(treeItems[i].items) != 'undefined';
        if(treeItems[i].key == pid)
        {
            parentItem = treeItems[i];
            if(!hasChild) parentItem.items = [];
            hasCurrentItem = false;
            for(j in parentItem.items)
            {
                if(parentItem.items[j].key == treeItem.key) hasCurrentItem = true;
            }
            if(parentItem.checked != undefined) treeItem.checked = parentItem.checked;
            if(!hasCurrentItem) parentItem.items.push(treeItem);
        }
        else if(hasChild)
        {
            treeItems[i].items = appendItems(treeItems[i].items, treeItem, pid);
        }
    }
    return treeItems;
};

window.getSelectedDepts = function()
{
    var selectedDepts = [];
    $('#deptList').find('.item-checkbox.checked').each(function()
    {
        id = $(this).closest('.tree-item').attr('z-key');
        selectedDepts.push(id);
    });
    return selectedDepts;
};

window.submitSelectedDepts = function()
{
    const selectedDepts = getSelectedDepts();
    if(selectedDepts.length == 0) return zui.Modal.alert(noDeptError);

    let link = $.createLink('webhook', 'bind', "id=" + webhookID);
    link    += link.indexOf('?') >= 0 ? '&' : '?';
    link    += "selectedDepts=" + selectedDepts.join(',');
    $('.actions .save').attr('disabled', 'disabled');
    loadPage(link);
};

window.setItemChecked = function(clickDept)
{
    const $startItem   = $('#deptList li[z-key="' + clickDept + '"]');
    const $checkbox    = $($startItem.children('[key="item"]'));
    const itemChecked  = $checkbox.hasClass('checked') && $checkbox.find('.item-checkbox').hasClass('checked');
    const noAllChecked = $checkbox.hasClass('checked') && !$checkbox.find('.item-checkbox').hasClass('checked');

    $startItem.find('[key="item"]').toggleClass('checked', !itemChecked && !noAllChecked);
    $startItem.find('.item-checkbox').toggleClass('checked', !itemChecked && !noAllChecked);
    $startItem.find('.item-checkbox').find('input[type=checkbox]').prop('checked', !itemChecked && !noAllChecked);
};

const childrenLoaded = [];
window.loadChildrenTree = function(e)
{
    if(webhookType != 'feishuuser') return;

    const $deptList = $('#deptList');
    const $this     = $(e.target);
    const $li       = $this.closest('li');
    const deptID    = $li.attr('z-key');

    let $checkbox = null;
    if($this.hasClass('item-checkbox')) $checkbox = $this;
    if($this.parent().hasClass('item-checkbox')) $checkbox = $this.parent();
    if($checkbox) return setItemChecked(deptID);

    if(childrenLoaded.includes(deptID) || deptID == 1) return;
    childrenLoaded.push(deptID);

    $deptList.addClass('loading');
    $.post(feishuUrl, {departmentID: deptID}, function(deptTree)
    {
        const tree = $deptList.zui('tree');
        deptTree   = JSON.parse(deptTree);
        if(deptTree.length) tree.render(buildTreeItems(deptTree, tree.options.items));
        $deptList.removeClass('loading');
    });
};

window.loadDeptTree = function()
{
    if(webhookType == 'feishuuser')
    {
        $.getJSON(feishuUrl, function(deptTree)
        {
            $('#loadPrompt').remove();
            tree = new zui.Tree('#deptList', {checkbox: true, checkOnClick: true, defaultNestedShow: true, onClickItem: loadChildrenTree, items: buildTreeItems(deptTree)});
        });
    }
    else
    {
        tree = new zui.Tree('#deptList', {checkbox: true, checkOnClick: true, defaultNestedShow: true, items: buildTreeItems(deptTree)});
        $('#loadPrompt').remove();
    }
};
