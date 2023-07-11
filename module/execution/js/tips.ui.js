$(document).off('click', '.tipBtn').on('click', '.tipBtn', function()
{
    loadPage($(this).data('url'));
})
