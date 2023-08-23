$(function()
{
    // 修复连接曲线位置
    const fixReleasePathLine = function()
    {
        const $lines = $('.release-paths .release-line');
        $lines.each(function()
        {
            const $line = $(this);
            const $next = $line.next('.release-line');
            if ($next.length)
            {
                const $linkLine = $('<div class="release-link-line" />').insertAfter($line);
                const $startPos = $line.find(':first-child > a').position();
                $linkLine.css({
                    top: $startPos.top + 6,
                    left: $startPos.left + 12,
                    right: $next.find(':last-child').width(),
                    height: 172
                });
            }
        });

        const $paths = $('.release-path');
        $paths.each(function()
        {
            const $path = $(this);
            const $next = $path.next('.release-path');
            if ($next.length)
            {
                const $linkLine = $('<div class="release-link-line" />').insertAfter($path);
                const $startPos = $path.find('.grow > .release-line:last-child > :first-child > a').position();
                $linkLine.css({
                    top: $startPos.top + 6,
                    left: $startPos.left + 12,
                    right: $next.find('.grow > .release-line:first-child > :last-child').width(),
                    height: 172
                });
            }
        });
    };

    fixReleasePathLine();
    window.addEventListener('resize', fixReleasePathLine);
});
