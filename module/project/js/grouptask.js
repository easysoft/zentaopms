$(function()
{
    setTimeout(function(){fixedTheadOfList('#groupTable')}, 100);
    $(document).on('click', '.expandAll', function()
    {
        $('.expandAll').addClass('hidden');
        $('.collapseAll').removeClass('hidden');
        $('table#groupTable').find('tbody').each(function()
        {
            $(this).find('tr').addClass('hidden');
            $(this).find('tr.group-collapse').removeClass('hidden');
        })
    });
    $(document).on('click', '.collapseAll', function()
    {
        $('.collapseAll').addClass('hidden');
        $('.expandAll').removeClass('hidden');
        $('table#groupTable').find('tbody').each(function()
        {
            $(this).find('tr').removeClass('hidden');
            $(this).find('tr.group-collapse').addClass('hidden');
        })
    });
    $('.expandGroup').closest('.groupby').click(function()
    {
        $tbody = $(this).closest('tbody');
        $tbody.find('tr').addClass('hidden');
        $tbody.find('tr.group-collapse').removeClass('hidden');
    });
    $('.collapseGroup').click(function()
    {
        $tbody = $(this).closest('tbody');
        $tbody.find('tr').removeClass('hidden');
        $tbody.find('tr.group-collapse').addClass('hidden');
    });
})
