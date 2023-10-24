function loadProductSummary()
{
    let conditions = '';
    $('#conditions input[type=checkbox]').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);

    const params = window.btoa('conditions=' + conditions);
    const link   = $.createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=productSummary&params=' + params);
    loadPage(link, '#table-pivot-preview');
}
