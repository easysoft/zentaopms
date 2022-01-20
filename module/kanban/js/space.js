/* Make cards clickable. */
var $kanbans = $('.kanbans');
$kanbans.on('click', '.panel', function(e)
{
    if(!$(e.target).closest('.kanban-actions').length)
    {
        window.location.href = $(this).data('url');
    }
});

/* Display drop-down menu.*/
$('.panel').mouseenter(function(e)
{
    $('.kanban-actions' + e.currentTarget.parentElement.dataset.id).css('visibility','visible');
});

/* Hide drop-down menu. */
$('.panel').mouseleave(function(e)
{
    $('.kanban-actions').css('visibility','hidden');
    $('.dropdown').removeClass('open');
});

$(function()
{
    $('input[name^="showClosed"]').click(function()
    {
        var showClosed = $(this).is(':checked') ? 1 : 0;
        $.cookie('showClosed', showClosed, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});
