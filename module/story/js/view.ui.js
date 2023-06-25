$(document).on('mouseenter', '.detail-side .tab-pane ul li', function(e)
{
    $(this).find('.unlink').removeClass('hidden');
    e.stopPropagation();
});
$(document).on('mouseleave', '.detail-side .tab-pane ul li', function(e)
{
    $(this).find('.unlink').addClass('hidden');
    e.stopPropagation();
});
