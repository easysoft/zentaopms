$(function()
{
    $(document).on('change', '#year', function()
    {
        window.location.href = createLink('holiday', 'browse', 'year=' + $(this).val()) + '?_single=1';
    });
});
