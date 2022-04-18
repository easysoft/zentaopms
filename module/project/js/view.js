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
