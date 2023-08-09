$(function()
{
    /* Support to slide to next by click btn */
    $('#features').on('click', '.slide-feature-to-next', function()
    {
        $('#featuresCarousel').carousel('next');
    });

    /* Support to slide to prev by click btn */
    $('#features').on('click', '.slide-feature-to-prev', function()
    {
        $('#featuresCarousel').carousel('prev');
    });

    $('#features').on('click', '.btn-close-modal', function()
    {
        var feature = features[features.length - 1];
        $.post(createLink('misc', 'ajaxSaveViewed', 'feature=' + feature));
    });

    $('#featuresCarousel').on('slide.zui.carousel', function(e)
    {
        var $next      = $(e.relatedTarget);
        var $items     = $next.parent().children();
        var index      = $items.index($next);
        var itemsCount = $items.length;
        var isLastItem = index === itemsCount - 1;
        var isFirstItem = index === 0;
        var $features  = $('#features');

        var $nav = $('#featuresNav');
        $nav.find('li.active').removeClass('active');
        $nav.find('a[data-slide-to="' + index + '"]').parent().addClass('active');

        $features.toggleClass('is-last-item', isLastItem);
        $features.toggleClass('is-first-item', isFirstItem);
        if(isLastItem) $features.addClass('enabled');

        var feature = features[index - 1];
        if(feature) $.post(createLink('misc', 'ajaxSaveViewed', 'feature=' + feature));
    });

    $('#features').toggleClass('is-last-item', $('#featuresCarousel>.carousel-inner>.item').length < 2);
});
