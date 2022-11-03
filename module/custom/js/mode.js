$(function()
{
    $('#modeTab').addClass('btn-active-text');

    $('[name=mode]').change(function()
    {
        $('#submit').prop('disabled', 'disabled');
        if(mode != $(this).val()) $('#submit').prop('disabled', '');
        $('#program').closest('tr').toggle(mode == 'ALM' && $(this).val() == 'light');
    });

    $(document).on('click', '#submit', function()
    {
        var selectedMode = $('[name=mode]:checked').val();
        if(mode == selectedMode) return false;

        if(selectedMode == 'light')
        {
            $('#selectProgramModal').modal('show');
        }
        else
        {
            bootbox.confirm(changeModeTips, function(result)
            {
                if(result) $('#modeForm').submit();
            });
        }

        return false;
    });

    $(document).on('click', '.btn-save', function()
    {
        setTimeout(function()
        {
            $('#selectProgramModal').modal('hide');
            $('#modeForm').submit();
        }, 1000);
    });
})
