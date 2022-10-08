$('#cards').on('click', '.panel', function(e)
{
    if(!$(e.target).closest('.kanban-actions').length)
    {
        location.href = $(this).data('url');
    }
});
