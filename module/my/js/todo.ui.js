$(document).on('change', '#date', function()
{
    const date  = $('#date').val().replaceAll('-', '');
    loadPage($.createLink('my', 'todo', 'date=' + date), '#mainContent');
});
