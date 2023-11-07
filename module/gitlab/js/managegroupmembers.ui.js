window.getMenu = function(item)
{
    const selectedUsers = [];
    $('.pick-value[name^=id]').each(function()
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

    row.find('[data-name="id"]').find('.picker-box').on('inited', function(_, info)
    {
        if(hasAccessUsers[data.id])
        {
            const users = [{text: data.name, value: data.id}];
            info[0].render({disabled: true, required: true, items: users, defaultValue: data.id});
        }
    });
}
