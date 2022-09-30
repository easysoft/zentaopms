$(function()
{
    $('#modeTab').addClass('btn-active-text');

    $('[name=mode]').change(function()
    {
        $('#submit').prop('disabled', 'disabled');
        if(mode != $(this).val()) $('#submit').prop('disabled', '');
        $('#program').closest('tr').toggle(mode == 'new' && $(this).val() == 'lean');
    });


    $(document).on('click', '#submit', function()
    {
        if(mode == $('[name=mode]:checked').val()) return false;

        if($(this).hasClass('canSubmit'))
        {
            $(this).removeClass('canSubmit');
            $('#confirmModal').modal('hide');
            return true;
        }

        $(this).addClass('canSubmit');

        var $mode = $('[name=mode]:checked').val();
        var confirmTitle   = changeModeTitleTips[$mode];
        var confirmContent = changeModeContentTips[$mode]

        $('#confirmModal .modal-title').html(confirmTitle);
        $('#confirmModal .modal-body').html(confirmContent);

        $('#confirmModal').modal('show');

        return false;
    });

    $(document).on('click', '.btn-confirm', function()
    {
        $('#submit').click();
    });

    $('#confirmModal').on('hide.zui.modal', function()
    {
        $('#submit').removeClass('canSubmit');
    });
})
