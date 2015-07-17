$('#conditions input:checkbox').change(function()
{
    var conditions = '';
    $('#conditions input:checkbox').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);
    location.href = createLink('report', 'productSummary', 'conditions=' + conditions);
})
