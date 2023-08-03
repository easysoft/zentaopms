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

    $('#featureBar').on('click', '.group-collapse-all', function()
    {
        $(this).closest('.nav-item').addClass('hidden');
        $('.group-expand-all').closest('.nav-item').removeClass('hidden');

        $('#tasksTable').find('tbody tr').addClass('hidden');
        $('#tasksTable').find('tbody tr.group-summary').removeClass('hidden');
    })

    $('#featureBar').on('click', '.group-expand-all', function()
    {
        $(this).closest('.nav-item').addClass('hidden');
        $('.group-collapse-all').closest('.nav-item').removeClass('hidden');

        $('#tasksTable').find('tbody tr').removeClass('hidden');
        $('#tasksTable').find('tbody tr.group-summary').addClass('hidden');
    })
})
