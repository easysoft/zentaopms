window.backup = function(e)
{
    if(backupError)
    {
        alert(backupError);
        return;
    }

    var backupBtn = $(e);
    backupBtn.toggleClass('loading');
    backupBtn.text(getSpaceLoading);
    link = $.createLink('backup', 'ajaxGetDiskSpace');
    $.get(link, function (data)
    {
        backupBtn.toggleClass('loading');
        backupBtn.text(startBackup);
        if (data)
        {
            if (data.needSpace > data.freeSpace)
            {
                var tips = alertTips.replace('NEED_SPACE', (data.needSpace / (1024 * 1024 * 1024)).toFixed(2));
                zui.Modal.alert({message: tips});
            }
            else
            {
                backupData();
            }
        }
    }, 'json');
}

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
    zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
    $('#waiting').addClass('show');

    timeID = setInterval(function () {
        $.get($.createLink('backup', 'ajaxGetProgress'), function (data) {
            $('#waiting .modal-content #message').html(data);
        });
    }, 1000);
}

window.getCellSpan = function(cell)
{
    if((cell.col.name == 'time' || cell.col.name == 'actions') && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}