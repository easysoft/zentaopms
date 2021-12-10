/* Make cards clickable. */
var $kanbans = $('.kanbans');
$kanbans.on('click', '.panel', function(e)
{
    if(!$(e.target).closest('.panel-actions').length)
    {
        window.location.href = $(this).data('url');
    }
});
