window.onRenderRow = function(row, rowIdx, data)
{
    if(!data) return;

    row.find('[data-name="account"]').find('.picker-box').on('inited', function(_, info)
    {
        if(hasAccessUsers.includes(data.account))
        {
            info[0].options.readonly = true;
            info[0].options.required = true;
        }
    });
    if(data.role == 'manager')
    {
        row.find('[data-type="delete"]').addClass('hidden');
    }
}

window.getMenu = function(item)
{
    const selectedUsers = [];
    $('.pick-value[name^=account]').each(function()
    {
        const account = $(this).val();
        if(account) selectedUsers.push(account);
    });

    item.disabled = selectedUsers.includes(item.value);
    return item;
}
