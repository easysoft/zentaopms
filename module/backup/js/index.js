$(function ()
{
    $('.backup').click(function()
    {
        if (backupError)
        {
            alert(backupError);
            return;
        }
        var that = this;
        $(that).toggleClass('loading');
        $(that).text(getSpaceLoading);
        link = createLink('backup', 'ajaxGetDiskSpace');
        $.get(link, function (data)
        {
            $(that).toggleClass('loading');
            $(that).text(startBackup);
            if (data)
            {
                if (data.needSpace > data.freeSpace)
                {
                    alertTips = alertTips.replace('NEED_SPACE', (data.needSpace / (1024 * 1024 * 1024)).toFixed(2));
                    $('#spaceConfirm').find('p').text(alertTips);
                    $('#spaceConfirm').modal('show', 'center');
                }
                else
                {
                    backupData();
                }
            }
        }, 'json');
    });

    $('.rmPHPHeader').click(function () {
        $('#waiting .modal-body #backupType').html(rmPHPHeader);
        $('#waiting .modal-content #message').hide();
        $('#waiting').modal('show');
    });

    $('.restore').click(function ()
    {
        url = $(this).attr('href');
        bootbox.confirm(confirmRestore, function (result)
        {
            if (result)
            {
                $('#waiting .modal-body #backupType').html(restore);
                $('#waiting .modal-content #message').hide();
                $('#waiting').modal('show');

                $.getJSON(url, function (response)
                {
                    $('#waiting').modal('hide');
                    bootbox.alert(response.message);
                });
            }
            else
            {
                return location.reload();
            }
        });

        return false;
    });
});

function backupData() {
    var timeID = null;
    $.ajax({
        url: $('.backup').attr('data-link'),
        success: function (data) {
            clearInterval(timeID);
            $('#waiting .modal-body #message').append(data);
            setTimeout(function () {return location.reload();}, 2000);
        },
        error: function (request, textstatus, error) {
            clearInterval(timeID);
            if (textstatus == 'timeout') $('#waiting .modal-body #message').append("<p class='text-danger'>" + backupTimeout + '</p>');
            setTimeout(function () {return location.reload();}, 2000);
        }
    });

    $('#waiting .modal-body #backupType').html(backup);
    $('#waiting').modal('show');

    timeID = setInterval(function () {
        $.get(createLink('backup', 'ajaxGetProgress'), function (data) {
            $('#waiting .modal-content #message').html(data);
        });
    }, 1000);
}
