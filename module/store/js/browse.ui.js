var checkedCategories = {};

/**
 * 菜单点击刷新数据。
 * Refresh data when click menu item.
 */
window.treeClick = function(info)
{
    if (info.item.items && info.item.items.length > 0) return;
    if(checkedCategories[info.item.id] != undefined)
    {
        $('#' + info.item.id).parent().removeClass('selected');
        delete checkedCategories[info.item.id];
    }
    else
    {
        $('#' + info.item.id).parent().addClass('selected');
        checkedCategories[info.item.id] = true;
    }

    const form = new FormData();
    form.append('keyword', $('#name').val());
    Object.keys(checkedCategories).forEach((id) => form.append('categories[]', id));

    postAndLoadPage(link, form, '#cloudAppContainer', {partial: true});
}

/**
 * 安装应用。
 * Install app.
 */
window.installApp = function()
{
    var confirm = $(this).data('confirm');
    if(confirm)
    {
        zui.Modal.confirm(confirm).then(result =>
            {
                if(!result) return;
                zui.Modal.open({
                    url: $(this).data('url'),
                    id: 'installModal'
                });
            });
        return;
    }

    zui.Modal.open({
        url: $(this).data('url'),
        id: 'installModal'
    });
}

$('#mainContent').on('keydown', '#name', function(event)
{
    if (event.key === 'Enter')
    {
        const form = new FormData();
        form.append('keyword', $('#name').val());
        Object.keys(checkedCategories).forEach((id) => form.append('categories[]', id));

        postAndLoadPage(link, form);
    }
});
