$(function()
{
    $('#installForm').on('submit', function(event)
    {
        event.preventDefault();

        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.installing + '</div>',
        });

        $.post($('#installForm').attr('action'), $('#installForm').serializeArray()).done(function(response)
        {
            loadingDialog.modal('hide');

            let res = JSON.parse(response);
            if(res.result == 'success')
            {
                config.onlybody = 'no';
                window.parent.$.apps.open(res.locate, 'space');
            }
            else
            {
                alert(res.message);
            }
        });
    });

    $("input[type=radio][name='dbType']").on('change', function(event)
    {
        if(event.target.value == 'sharedDB')
        {
            $("select[name=dbService]").closest('td').show();
        }
        else
        {
            $("select[name=dbService]").closest('td').hide();
        }
    });

    $(".advanced a").on('click', function(event)
    {
        let downArrow = $(".advanced a .icon-chevron-double-down");
        let upArrow   = $(".advanced a .icon-chevron-double-up");
        if(downArrow.length >0 ) downArrow.removeClass('icon-chevron-double-down').addClass('icon-chevron-double-up');
        if(upArrow.length >0 ) upArrow.removeClass('icon-chevron-double-up').addClass('icon-chevron-double-down');
    });
});
