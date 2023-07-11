$('.assigntome-block .nav.nav-tabs').on('show', function(event, info)
{
    let activeMore = false;
    $(this).find('.menu-item a[data-toggle=tab]').each(function()
    {
        if($(this).hasClass('active'))
        {
            $(this).closest('.nav-item.nav-switch').find('a[data-toggle=dropdown] span').html($(this).html());
            $(this).closest('.nav-item.nav-switch').find('a[data-toggle=dropdown]').addClass('active');
            activeMore = true;
        }
    });
    if(!activeMore)
    {
        $(this).find('.nav-item a[data-toggle=dropdown] span').html(moreLabel);
        $(this).find('.nav-item a[data-toggle=dropdown]').removeClass('active');
    }
});
