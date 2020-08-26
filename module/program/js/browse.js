$(function()
{
    $('input[name^="mine"]').click(function()
    {
        var mine = $(this).is(':checked') ? 1 : 0;
        $.cookie('mine', mine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    /* Auto resize cards size */
    var minCardWidth = 280;
    var $cards = $('#cards');
    var resizeCards = function()
    {
        var cardsWidth = $cards.width();
        var bestColsSize = 1;
        while((cardsWidth / (bestColsSize + 1)) > minCardWidth)
        {
            bestColsSize++;
        }
        $cards.children('.col').css('width', (100 / bestColsSize) + '%');
    };
    resizeCards();
    $(window).on('resize', resizeCards);

    /* Auto resize stages */
    $cards.find('.program-stages-container').each(function()
    {
        var $container = $(this);
        var $row = $container.children();
        var totalWidth = 0;
        $row.children().each(function()
        {
            var $item = $(this);
            $item.css('left', totalWidth);
            totalWidth += $item.width();
        });
        $row.css('minWidth', totalWidth);
    });

    /* Make cards clickable */
    $cards.on('click', '.panel', function(e)
    {
        if(!$(e.target).closest('.panel-actions').length)
        {
            window.location.href = $(this).data('url');
        }
    })

    $('#programTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('project', 'updateOrder'), {'projects' : list, 'orderBy' : orderBy});
    });
})
