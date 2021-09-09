$(function()
{
    /* Support to slide to next by click btn */
    $('#features').on('click', '.slide-feature-to-next', function()
    {
        $('#featuresCarousel').carousel('next');
    });

    $('#featuresCarousel').on('slide.zui.carousel', function(e)
    {
        var $next      = $(e.relatedTarget);
        var $items     = $next.parent().children();
        var index      = $items.index($next);
        var itemsCount = $items.length;
        var isLastItem = index === itemsCount - 1;
        var $features  = $('#features');

        var $nav = $('#featuresNav');
        $nav.find('li.active').removeClass('active');
        $nav.find('a[data-slide-to="' + index + '"]').parent().addClass('active');


        $features.toggleClass('is-last-item', isLastItem);
        if(isLastItem) $features.addClass('enabled');
    });

    $('#features').toggleClass('is-last-item', $('#featuresCarousel>.carousel-inner>.item').length < 2);
});
