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
})
