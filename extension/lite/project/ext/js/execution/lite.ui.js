$(document).off('click', '.kanban-card').on('click', '.kanban-card', function(e)
{
    if(!$(e.target).closest('.kanban-actions').length) loadPage($(this).data('url'));
});
