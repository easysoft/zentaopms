$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'same')
    {
        var index = $(select).parent().index();
        var value = $(select).parent().parent().prev('tr').find('td').eq(index).find('select').val();
        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
})
