window.getMenu = function(item)
{
    const selectedUsers = [];
    $('.pick-value[name^=account]').each(function()
    {
        const name = $(this).val();
        if(name) selectedUsers.push(name);
    });

    item.disabled = selectedUsers.includes(item.value);
    return item;
}

window.onRenderRow = function(row, rowIdx, data)
{
    if(!data) return;

    row.find('[data-name="account"]').find('.picker-box').on('inited', function(_, info)
    {
        if(hasAccessUsers[data.account])
        {
            const users = [{text: data.name, value: data.account}];
            info[0].render({disabled: true, required: true, items: users, defaultValue: data.account});
        }
    });
}
