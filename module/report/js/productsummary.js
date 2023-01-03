$('#conditions input:checkbox').change(function()
{
    var conditions = '';
    $('#conditions input:checkbox').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);

    var params = window.btoa('conditions=' + conditions);
    var link = createLink('report', 'preview', 'dimension=' + dimension + '&group=' + group + '&module=report&method=productSummary&params=' + params);
    location.href = link;
})
