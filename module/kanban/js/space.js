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
    $('.kanban-actions' + e.currentTarget.parentElement.dataset.id).css('display','block');
});

/* Hide drop-down menu. */
$('.panel').mouseleave(function(e)
{
    $('.kanban-actions').css('display','none');
    $('.dropdown').removeClass('open');
});
