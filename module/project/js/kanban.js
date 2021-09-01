$(function()
{
    $("div[class^='board-doing-']").height($('.board-doing-project').height());

    $('.board-program').each(function()
    {
        var boardWaitCount   = $(this).find('.board-wait .board-item').length;
        var boardClosedCount = $(this).find('.board-closed .board-item').length;
        var boardDoingCount  = $(this).find('.board-doing-project .board-item').length;

        if((boardWaitCount > 5 || boardClosedCount > 5) && (boardWaitCount > boardDoingCount || boardClosedCount > boardDoingCount))
        {
            var boardHeight = 0;
            if(boardDoingCount >= 5)
            {
                boardHeight = $('.board-doing-project').outerHeight(true) * boardDoingCount;
            }
            else
            {
                var boardHeight = $(this).find('.board-project .board-item').outerHeight(true) * 5;
            }

            $(this).find('.board-project').css("height", boardHeight);
            $(this).find('.board-project').css("overflow", 'auto');
        }
    });
})
