loadedDept = [];
window.loadChildDept = function(event, node)
{
    if(typeof(node.parentKey) == 'undefined') return;

    var tree = $('#deptList').zui('tree');
    var options = tree.options;
    var departmentID = node.key;
    if(loadedDept.includes(departmentID)) return;

    $.ajax(
    {
        type: "post",
        url: feishuUrl,
        data: {departmentID: departmentID},
        dataType: "json",
        async: true,
        success: function(jsonData)
        {
            options.items = buildTreeItems(jsonData, options.items);
            tree.render(options);
        }
    });
    loadedDept.push(departmentID);
};

window.buildTreeItems = function(deptTree, treeItems)
{
    if(typeof(treeItems) == 'undefined') treeItems = [];
    for(i in deptTree)
    {
        let dept     = deptTree[i];
        let treeItem = {key: dept.id, text: dept.name, onClick: loadChildDept};
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
    var nodes = $('#deptList').tree('getChecks');
    var selectedDepts = [];
    for(i in nodes)
    {
        id = nodes[i].split(':').pop();
        selectedDepts.push(id);
    }
    selectedDepts = selectedDepts.join(',');

    var link = $.createLink('webhook', 'bind', "id=" + webhookID);
    link    += link.indexOf('?') >= 0 ? '&' : '?';
    link    += "selectedDepts=" + selectedDepts;
    $('.actions .save').attr('disabled', 'disabled');
    loadPage(link);
};

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
