$(function()
{
    $('#reposBox input:checkbox').change(function()
    {
        $(this).closest('.repo').toggleClass('checked', $(this).prop('checked'));
    });
});
