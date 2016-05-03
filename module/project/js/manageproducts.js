$(function()
{
    $('#productsBox input:checkbox').each(function()
    {
        var cb = $(this);
        if(cb.prop('checked')) cb.closest('.col-sm-4').addClass('checked');
    });

    $('#productsBox input:checkbox').change(function()
    {
        var cb = $(this);
        cb.closest('.col-sm-4').toggleClass('checked', cb.prop('checked'));
    });

    var $hasBranch = $('#productsBox .col-sm-4 .col-sm-6');
    if($hasBranch.size() > 0)
    {
        newPadding = ($hasBranch.parent().css('padding-top').replace('px', '') - 1) + 'px';
        $hasBranch.parent().css({"padding-top" : newPadding, "padding-bottom" : newPadding});
    }
})
