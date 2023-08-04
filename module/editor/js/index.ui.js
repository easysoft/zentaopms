window.initModuleTree = function()
{
    zui.create("tree", "#moduleTree", {items: moduleTree, onClickItem: function(menu)
    {
        let e    = menu.event;
        let $this = $(e.target);
        let item  = menu.item;
        if(item.url != '')
        {
            console.log(item.url);
            $this.attr('target', 'extendWin');

            $this.closest('#moduleTree').find('li.active').removeClass('active');
            $this.closest('li.tree-item').addClass('active');
            $this.closest('li.tree-item.has-nested-menu').addClass('active');
        }

        e.stopPropagation();
    }});
}
initModuleTree();
