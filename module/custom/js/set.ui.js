window.addRow = function()
{
    if($('#customFieldRow .form-label').text() == 'addRow')
    {
        $('#customFieldRow .form-label').html('<input class="form-control key-row" type="text" autocomplete="off" name="keys[]">');
    }

    $(this).closest('.form-row').after($('#customFieldRow').html());
    $(this).closest('.form-row').next('.form-row').find('.add-item').on('click', addRow);
    $(this).closest('.form-row').next('.form-row').find('.del-item').on('click', removeRow);
}

window.removeRow = function()
{
    $(this).closest('.form-row').remove();
}
