$(function()
{
    $('.check-all input[type=checkbox]').change(function()
    {
        var checked = $(this).prop('checked');
        $(this).parents('tr').find('input[type=checkbox]').prop('checked', checked);
    });
});
