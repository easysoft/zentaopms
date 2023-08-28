$(function()
{
    $('.backup').click(function()
    {
        if(backupError)
        {
            alert(backupError);
            return;
        }
        var that = this;
        $(that).toggleClass('loading');
        $(that).text(getSpaceLoading);
        link = createLink('backup', 'ajaxGetkDiskSpace');
        $.get(link, function(data)
        {
            $(that).toggleClass('loading');
            $(that).text(startBackup);
            if(data)
            {
                if(data.needSpace > data.freeSpace)
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

    $('.rmPHPHeader').click(function()
    {
        $('#waitting .modal-body #backupType').html(rmPHPHeader);
        $('#waitting .modal-content #message').hide();
        $('#waitting').modal('show');
    });

    $('.restore').click(function()
    {
        url = $(this).attr('href');
        bootbox.confirm(confirmRestore, function(result)
        {
            if(result)
            {
                $('#waitting .modal-body #backupType').html(restore);
                $('#waitting .modal-content #message').hide();
                $('#waitting').modal('show');

                $.getJSON(url, function(response)
                {
                    $('#waitting').modal('hide');
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

function backupData()
{
    var timeID = null;
    $.ajax({
        url: $('.backup').attr('data-link'),
        success: function(data)
        {
            clearInterval(timeID);
            $('#waitting .modal-body #message').append(data);
            setTimeout(function(){return location.reload();}, 2000);
        },
        error: function(request, textstatus, error)
        {
            clearInterval(timeID);
            if(textstatus == 'timeout') $('#waitting .modal-body #message').append("<p class='text-danger'>" + backupTimeout + '</p>');
            setTimeout(function(){return location.reload();}, 2000);
        }
    });

    $('#waitting .modal-body #backupType').html(backup);
    $('#waitting').modal('show');

    timeID = setInterval(function()
    {
        $.get(createLink('backup', 'ajaxGetProgress'), function(data)
        {
            $('#waitting .modal-content #message').html(data);
        });
    }, 1000);
}
