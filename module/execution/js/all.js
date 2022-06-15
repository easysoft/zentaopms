$(function()
{
    $('#executionTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('execution', 'updateOrder'), {'executions' : list, 'orderBy' : orderBy});
    });

    var nameWidth = $('#executionsForm thead th.c-name').width();
    if(nameWidth < 150 && !useDatatable) $('#executionsForm thead th.c-name').css('width', '150px');

    toggleFold('#executionsForm', unfoldExecutions, projectID, 'execution');

    $('.table td.has-child > .plan-toggle').each(function()
    {
        var fold = $(this).hasClass('collapsed');
        var parentID = $(this).closest('tr').attr('data-id');
        if(fold) $('.parent-' + parentID).hide();
        if(!fold) $('.parent-' + parentID).show();
    });

    if(useDatatable)
    {
        $('.table td.has-child > .plan-toggle').click(function()
        {
            var parentID = $(this).closest('tr').attr('data-id');
            $('.parent-' + parentID).toggle();
        });
    }
});

function byProduct(productID, projectID, status)
{
    location.href = createLink('project', 'all', "status=" + status + "&project=" + projectID + "&orderBy=" + orderBy + '&productID=' + productID);
}
