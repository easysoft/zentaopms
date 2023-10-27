window.editItem = function(data)
{
    const modal = zui.Modal.open({
        url: $.createLink('tree', 'edit', 'moduleID=' + data.item.id + '&type=' + data.item.editType),
    });
};
