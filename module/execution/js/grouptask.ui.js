$(function()
{
    $('#tasksTable').on('click', '.groupBtn', function()
    {
        let groupIndex = $(this).closest('tr').data('id');
        $('#tasksTable').find('tr[data-id="' + groupIndex + '"]').addClass('hidden');
        $('#tasksTable').find('tr.group-summary[data-id="' + groupIndex + '"]').removeClass('hidden');
    })

    $('#tasksTable').on('click', '.summaryBtn', function()
    {
        let groupIndex = $(this).closest('tr').data('id');
        $('#tasksTable').find('tr[data-id="' + groupIndex + '"]').removeClass('hidden');
        $('#tasksTable').find('tr.group-summary[data-id="' + groupIndex + '"]').addClass('hidden');
    })
})
