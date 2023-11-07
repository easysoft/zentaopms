window.getMenu = function(item)
{
    const selectedTags = [];
    $('.pick-value[name^=name]').each(function()
    {
        const name = $(this).val();
        if(name) selectedTags.push(name);
    });

    item.disabled = selectedTags.includes(item.text);
    return item;
}

window.onRenderRow = function(row, rowIdx, data)
{
    if(!data) return;

    row.find('[data-name="name"]').find('.picker-box').on('inited', function(_, info)
    {
        if(hasAccessTags[data.name])
        {
            const tags = [{text: data.name, value: data.name}];
            info[0].render({disabled: true, required: true, items: tags, defaultValue: data.name});
        }
    });
}
