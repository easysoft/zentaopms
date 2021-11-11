$(function()
{
    $('#productsBox input:checkbox').each(function()
    {
        var $cb = $(this);
        if($cb.prop('checked')) $cb.closest('.product').addClass('checked');
    });

    $('#productsBox input:checkbox').change(function()
    {
        var $cb = $(this);
        $cb.closest('.product').toggleClass('checked', $cb.prop('checked'));
    });

    $("select[id^=branch]").change(function()
    {
        var checked = $(this).closest('div').hasClass('checked');
        if(!checked)
        {
            $(this).closest('div').addClass('checked');
            $(this).closest('div').find("input[id^=products]").prop('checked', true);
        }
    });
});
