window.addRow = function(e)
{
    if($('#stageFieldRow .form-label').text() == 'addRow')
    {
        $('#stageFieldRow .form-label').html('<input class="form-control key-row" type="text" autocomplete="off" name="keys[]">');
    }

    $(e.target).closest('.form-row').after($('#stageFieldRow').html());
    $(e.target).closest('.form-row').next('.form-row').find('.add-item').on('click', addRow);
    $(e.target).closest('.form-row').next('.form-row').find('.del-item').on('click', removeRow);
}

window.removeRow = function()
{
    $(this).closest('.form-row').remove();
}
