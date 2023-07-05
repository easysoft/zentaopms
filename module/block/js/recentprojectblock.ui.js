$(function()
{
    $('.recentproject-block .cards .project-stages-container').each(function()
    {
        var $container = $(this);
        var $row       = $container.children();
        var totalWidth = 0;
        $row.children().each(function()
        {
            var $item = $(this);
            $item.css('left', totalWidth);
            totalWidth += $item.width();
        });
        $row.css('minWidth', totalWidth);
    });
})
