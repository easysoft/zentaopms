$(function()
{
    $('#productsBox input:checkbox').each(function()
    {
        var cb = $(this);
        if(cb.attr('checked')) cb.closest('label').addClass('checked');
    });

    $('#productsBox input:checkbox').change(function()
    {
        var cb = $(this);
        cb.closest('label').toggleClass('checked', cb.attr('checked'));
    });
})
