function setModal4List(colorboxClass, replaceID)
{
    replaceID = 'bugList';
    $('.' + colorboxClass).colorbox(
    {
        width:900,
        height:500,
        iframe:true,
        transition:'none',

        onCleanup:function()
        {
            saveWindowSize();

            var link = self.location.href;
            $.get(link, {onlybody: "yes"}, function(data)
            {
                $('#' + replaceID).replaceWith(data)
                setModal4List(colorboxClass, replaceID)

                $('.colored').colorize();
                $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
            });
        }
    });
}
