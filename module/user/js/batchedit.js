$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).parents('td').index();
        var value = $(select).parents('tr').prev('tr').find('td').eq(index).find('select').val();
        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
})
$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).parents('td').index();
        var value = $(this).parents('tr').prev('tr').find('td').eq(index).find('select').val();
        $(this).val(value);
    }
})
