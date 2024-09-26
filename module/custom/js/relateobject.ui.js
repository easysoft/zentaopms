$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const formData = dtable.$.getFormData();
    const url      = $(this).data('url');
    const form     = new FormData();
    checkedList.forEach((id) =>
    {
        let key = 'relation[' + id + ']';
        form.append(key, formData[key]);
    });

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

window.renderCell = function(result, info)
{
    if(info.col.name == 'relation')
    {
        let relationItems = [];
        $.each(relationPairs, function(key, value){relationItems.push({'value': key, 'text': value});});
        result[0].children.props.required = true;
        result[0].children.props.items    = relationItems;
    }
    return result;
}

window.switchObject = function(value)
{
    const url = $.createLink('custom', 'relateObject', 'objectID=' + objectID + '&objectType=' + objectType + '&relatedObjectType=' + value);
    loadModal(url);
}
