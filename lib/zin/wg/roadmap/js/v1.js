$(function()
{
    // 修复连接曲线位置
    const fixReleasePathLine = function()
    {
        const $lines = $('.release-paths .release-line');
        $lines.each(function()
        {
            const $line = $(this);
            const $next = $line.next();
            let $nextLine, $linkLine;
            if($next.hasClass('release-line'))
            {
                $nextLine = $next;
            }
            else if($next.hasClass('release-link-line'))
            {
                $linkLine = $next;
                $nextLine = $next.next();
            }
            if ($nextLine && $nextLine.length)
            {
                if(!$linkLine) $linkLine = $('<div class="release-link-line" />').insertAfter($line)
                const $startPos = $line.find(':first-child > a').position();
                const $endPos = $nextLine.find(':last-child > a').position();
                $linkLine.css({
                    top: $startPos.top + 6,
                    left: $startPos.left + 12,
                    width: $endPos.left - $startPos.left,
                    height: 172
                });
            }
        });

        const $paths = $('.release-path');
        $paths.each(function()
        {
            const $path = $(this);
            const $next = $path.next();
            let $nextPath, $linkLine;
            if($next.hasClass('release-path'))
            {
                $nextPath = $next;
            }
            else if($next.hasClass('release-link-line'))
            {
                $linkLine = $next;
                $nextPath = $next.next();
            }
            if ($nextPath && $nextPath.length)
            {
                if(!$linkLine) $linkLine = $('<div class="release-link-line" />').insertAfter($path);
                const $startPos = $path.find('.grow > .release-line:last-child > :first-child > a').position();
                const $endPos = $nextPath.find('.grow > .release-line:first-child > :last-child > a').position();
                $linkLine.css({
                    top: $startPos.top + 6,
                    left: $startPos.left + 12,
                    width: $endPos.left - $startPos.left,
                    height: 172
                });
            }
        });
    };

    fixReleasePathLine();
    window.addEventListener('resize', fixReleasePathLine);
});
