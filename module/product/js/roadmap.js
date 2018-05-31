$(function()
{
    // 修复连接曲线位置
    var fixReleasePathLine = function()
    {
        $('.release-paths.active').each(function()
        {
            var $lines = $(this).find('.release-line');
            var linesCount = $lines.length;
            if (linesCount < 2) return;
            $lines.each(function(idx)
            {
                if (idx >= (linesCount - 1)) return;
                var $prev = $(this);
                var $next = $lines.eq(idx + 1);
                var $line = $prev.next('.release-link-line');
                if(!$line.length)
                {
                    $line = $('<div class="release-link-line" />').insertAfter($prev);
                }
                $line.css(
                {
                    top: $prev.position().top + 90,
                    right: $next.find('li:last-child').width() - 4,
                    height: $prev.outerHeight() - 10
                });
            });
        });
    };

    fixReleasePathLine();
    $(window).on('resize shown.zui.tab', fixReleasePathLine);
});
