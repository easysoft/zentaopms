$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        var editProduct = $(this).is(':checked') ? 1 : 0;
        $.cookie('showProductBatchEdit', editProduct, {expires: config.cookieLife, path: config.webRoot});
        dtableWithZentao.render({checkable: editProduct,
            footer() {
                const statistic = () => {
                    const checkedCount = this.getChecks().length;
                    const text = editProduct && checkedCount ? checkedProjects.replace('%s', checkedCount) : productSummary;

                    return [{children: text, className: 'text-dark'}];
                };
                if(editProduct) return ['checkbox', 'toolbar', statistic, 'flex', 'pager'];
                return [statistic, 'flex', 'pager'];
            },
        });
    });

    var isEditMode = $('input#showEdit1').is(':checked');
    dtableWithZentao.render({
        checkable: isEditMode,
        footer() {
            const statistic = () => {
                const checkedCount = this.getChecks().length;
                const text = isEditMode && checkedCount ? checkedProjects.replace('%s', checkedCount) : productSummary;

                return [{children: text, className: 'text-dark'}];
            };
            if(isEditMode) return ['checkbox', 'toolbar', statistic, 'flex', 'pager'];
            return [statistic, 'flex', 'pager'];
        },
    });
});
