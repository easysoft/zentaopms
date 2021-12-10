/* Make cards clickable */
var $spaces = $('.kanbans');
$spaces.on('click', '.panel', function(e)
{
    if(!$(e.target).closest('.panel-actions').length)
    {
        window.location.href = $(this).data('url');
    }
});
