$(function()
{
    $('#measurementList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) 
        {
            list += $(data.list[i].item).attr('data-id') + ',';
        }
        $.post(createLink('measurement', 'updateOrder'), {'meas' : list, 'orderBy' : orderBy});
    });
});
