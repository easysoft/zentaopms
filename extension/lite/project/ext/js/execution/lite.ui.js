$(document).off('click', '.kanban-card').on('click', '.kanban-card', function(e)
{
    if(!$(e.target).closest('.kanban-actions').length) loadPage($(this).data('url'));
});

$(document).off('click', '[data-request=ajax-submit]').on('click', '[data-request=ajax-submit]', function()
{
    $.ajaxSubmit({"url": $(this).attr('data-url')});
})
