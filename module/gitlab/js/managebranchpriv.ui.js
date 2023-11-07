window.getMenu = function(item)
{
    const selectedBranches = [];
    $('.pick-value[name^=name]').each(function()
    {
        const name = $(this).val();
        if(name) selectedBranches.push(name);
    });

    item.disabled = selectedBranches.includes(item.text);
    return item;
}

window.onRenderRow = function(row, rowIdx, data)
{
    if(!data) return;

    row.find('[data-name="name"]').find('.picker-box').on('inited', function(_, info)
    {
        if(hasAccessBranches[data.name])
        {
            const branchs = [{text: data.name, value: data.name}];
            info[0].render({disabled: true, required: true, items: branchs, defaultValue: data.name});
        }
    });
}
