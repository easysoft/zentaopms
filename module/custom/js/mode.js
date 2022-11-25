$(function()
{
    $('#modeTab').addClass('btn-active-text');

    $('#useLight, #useALM').click(function()
    {
        var selectedMode = $(this).data('mode');
        $('#mode').val(selectedMode);

        if(selectedMode == 'light' && hasProgram)
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
    })

    $(document).on('click', '.btn-save', function()
    {
        setTimeout(function()
        {
            $('#selectProgramModal').modal('hide');
            $('#modeForm').submit();
        }, 1000);
    });
})
