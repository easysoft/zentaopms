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

(function()
{
    /* Expand or collapse text */
    function limitText()
    {
        var fullText;
        var limitText;
        var $text   = $(this);
        var options = $.extend({limitSize: 40, suffix: '…'}, $text.data());
        var text    = $text.text();
        if(text.length > options.limitSize)
        {
            fullText  = $text.html();
            limitText = text.substring(0, options.limitSize) + options.suffix;
            $text.text(limitText).addClass('limit-text-on');

            var $toggleBtn = options.toggleBtn ? $(options.toggleBtn) : $text.next('.text-limit-toggle');
            $toggleBtn.text($toggleBtn.data('textExpand'));
            $toggleBtn.on('click', function()
            {
                var isLimitOn = $text.toggleClass('limit-text-on').hasClass('limit-text-on');
                if(isLimitOn) $text.text(limitText);
                else $text.html(fullText);
                $toggleBtn.text($toggleBtn.data(isLimitOn ? 'textExpand' : 'textCollapse'));
            });
        }
        else
        {
            (options.toggleBtn ? $(options.toggleBtn) : $text.next('.text-limit-toggle')).hide();
        }
        $text.removeClass('hidden');
    };
    $.fn.textLimit = function(){return this.each(limitText);};
    $(function(){$('.text-limit').textLimit();});
})();
