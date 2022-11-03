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

        bootbox.confirm(changeModeTips, function(result)
        {
            if(result)
            {
                $('#modeForm').submit();
            }
        });

        return false;
    });
})
