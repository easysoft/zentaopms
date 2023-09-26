window.editItem = function(item)
{
    const modal = zui.Modal.open({
        url: $.createLink('tree', 'edit', 'moduleID=' + item.id + '&type=' + item.editType),
    });
};
