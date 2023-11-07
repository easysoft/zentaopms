window.initModuleTree = function()
{
    data = appendClickEvent(moduleTree);
    zui.create("tree", "#moduleTree", {items: data});
};

window.appendClickEvent = function(moduleTree)
{
    for(const tree of moduleTree)
    {
        if(tree.link != '') tree.onClick = openInExtend;
        if(typeof(tree.items) != 'undefined') tree.items = appendClickEvent(tree.items);
    }
    return moduleTree;
};

window.openInExtend = function(event, node)
{
    let $this = $(node.element);
    if(node.item.link == '') return;

    extendWin.location.href = node.item.link;

    $this.closest('#moduleTree').find('li.active').removeClass('active');
    $this.closest('li.tree-item').addClass('active');
    $this.closest('li.tree-item.has-nested-menu').addClass('active');
};

initModuleTree();
