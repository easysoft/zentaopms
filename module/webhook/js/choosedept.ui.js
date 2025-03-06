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
            if(!hasChild) treeItems[i].items = [];
            hasCurrentItem = false;
            for(j in treeItems[i].items)
            {
                if(treeItems[i].items[j].key == treeItem.key) hasCurrentItem = true;
            }
            if(!hasCurrentItem) treeItems[i].items.push(treeItem);
        }
        else if(hasChild)
        {
            treeItems[i].items = appendItems(treeItems[i].items, treeItem, pid);
        }
    }
    return treeItems;
};

window.submitSelectedDepts = function()
{
    var selectedDepts = [];
    $('#deptList').find('.item-checkbox.checked').each(function()
    {
        id = $(this).closest('.tree-item').attr('z-key');
        selectedDepts.push(id);
    });
    if(selectedDepts.length == 0) return zui.Modal.alert(noDeptError);

    var link = $.createLink('webhook', 'bind', "id=" + webhookID);
    link    += link.indexOf('?') >= 0 ? '&' : '?';
    link    += "selectedDepts=" + selectedDepts.join(',');
    $('.actions .save').attr('disabled', 'disabled');
    loadPage(link);
};

window.loadDeptTree = function()
{
    if(webhookType == 'feishuuser')
    {
        $.getJSON(feishuUrl, function(deptTree)
        {
            $('#loadPrompt').remove();
            tree = new zui.Tree('#deptList', {checkbox: true, checkOnClick: true, defaultNestedShow: true, items: buildTreeItems(deptTree)});
        });
    }
    else
    {
        tree = new zui.Tree('#deptList', {checkbox: true, checkOnClick: true, defaultNestedShow: true, items: buildTreeItems(deptTree)});
        $('#loadPrompt').remove();
    }
};
