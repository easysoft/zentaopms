var checkedCategories = {};

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

window.searchApp = function(event)
{
    if (event.key === 'Enter')
    {
        const keyword = $('#searchName').val();
        loadPage($.createLink('store', 'browse', `sortType=${sortType}&categoryID=${currentCategoryID}&keyword=${keyword}`));
    }
}
