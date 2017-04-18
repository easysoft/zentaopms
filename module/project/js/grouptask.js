$(function()
{
    setTimeout(function(){fixedTheadOfList('#groupTable')}, 100);
    $(document).on('click', '.expandAll', function()
    {
        $('.expandAll').addClass('hidden');
        $('.collapseAll').removeClass('hidden');
        $('table#groupTable').find('tbody').find('tr').addClass('hidden');
        $('table#groupTable').find('tbody').find('tr.group-collapse').removeClass('hidden');
    });
    $(document).on('click', '.collapseAll', function()
    {
        $('.collapseAll').addClass('hidden');
        $('.expandAll').removeClass('hidden');
        $('table#groupTable').find('tbody').find('tr').removeClass('hidden');
        $('table#groupTable').find('tbody').find('tr.group-collapse').addClass('hidden');
    });
    $('.expandGroup').closest('.groupby').click(function()
    {
        $tbody = $(this).closest('tbody');
        dataID = $(this).closest('tr').data('id');
        $tbody.find("tr[data-id='" + dataID + "']").addClass('hidden');
        $tbody.find("tr.group-collapse[data-id='" + dataID + "']").removeClass('hidden');
    });
    $('.collapseGroup').closest('td').click(function()
    {
        $tbody = $(this).closest('tbody');
        dataID = $(this).closest('tr').data('id');
        $tbody.find("tr[data-id='" + dataID + "']").removeClass('hidden');
        $tbody.find("tr.group-collapse[data-id='" + dataID + "']").addClass('hidden');
    });
})
