$(function()
{
    $('#reposBox input:checkbox').each(function()
    {
        var $cb = $(this);
        if($cb.prop('checked')) $cb.closest('.repo').addClass('checked');
    });

    $('#reposBox input:checkbox').change(function()
    {
        var $cb = $(this);
        $cb.closest('.repo').toggleClass('checked', $cb.prop('checked'));
    });
});
