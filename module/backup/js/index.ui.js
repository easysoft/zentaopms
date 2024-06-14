window.backup = function(e)
{
    if(backupError)
    {
        zui.Modal.alert(backupError);
        return;
    }
    var backupBtn = $('#actionBar .backup');
    backupBtn.toggleClass('loading');
    backupBtn.text(getSpaceLoading);
    $.get($.createLink('backup', 'ajaxGetDiskSpace'), function(data)
    {
        backupBtn.toggleClass('loading');
        backupBtn.text(startBackup);
        if(data)
        {
            if(data.needSpace > data.freeSpace)
            {
                zui.Modal.alert(alertTips.replace('NEED_SPACE', (data.needSpace / (1024 * 1024 * 1024)).toFixed(2)));
            }
            else
            {
                backupData();
            }
        }
    }, 'json');
}

window.getCellSpan = function(cell)
{
    if((cell.col.name == 'time' || cell.col.name == 'actions') && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}

function backupData()
{
    var timeID = null;
    $.ajax(
    {
        url: $.createLink('backup', 'backup', 'reload=yes'),
        success: function (data)
        {
            clearInterval(timeID);
            $('#message').append(data);
            setTimeout(function(){return loadCurrentPage();}, 2000);
        },
        error: function(request, textstatus, error)
        {
            clearInterval(timeID);
            if(textstatus == 'timeout') $('#message').append("<p class='text-danger'>" + backupTimeout + '</p>');
            setTimeout(function(){return loadCurrentPage();}, 2000);
        }
    });

    zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
    $('#waiting').addClass('show');

    timeID = setInterval(function()
    {
        $.get($.createLink('backup', 'ajaxGetProgress'), function(data)
        {
            if(data == '') return;
            $('#message').html(data);
        });
    }, 1000);
}

window.getCellSpan = function(cell)
{
    if((cell.col.name == 'time' || cell.col.name == 'actions') && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
};

window.restore = function(name)
{
    $.get($.createLink('backup', 'ajaxCheckBackupVersion', 'name=' + name), function(data)
    {
        zui.Modal.alert({message: data.message, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
        {
            if(res && data.canRestore)
            {
                $.ajaxSubmit({url: $.createLink('backup', 'restore', 'file=' + name)});

                zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
                $('#waiting').addClass('show');
                $('#waiting .modal-body #backupType').html(restoreLang);
                $('#waiting .modal-body #message').empty();
            }
        });
    }, 'json');
};

$(document).off('click', '.rmPHPHeader').on('click', '.rmPHPHeader', function(data)
{
    if($(this).hasClass('disabled')) return false;

    zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
    $('#waiting').addClass('show');
    $('#waiting .modal-body #backupType').html(rmPHPHeaderLang);
    $('#waiting .modal-body #message').empty();
});

let deleting = false;
$(document).off('click', '[data-confirm]').on('click', '[data-confirm]', function(){deleting = true;});
$(document).off('click', '.modal [z-key="cancel"]').on('click', '.modal [z-key="cancel"]', function(){deleting = false;});
$(document).off('click', '.modal [data-dismiss="modal"]').on('click', '.modal [data-dismiss="modal"]', function(){deleting = false;});
$(document).off('click', '.modal [z-key="confirm"]').on('click', '.modal [z-key="confirm"]', function()
{
    if(deleting) $('#main').addClass('loading');
});

window.backupInProgress = function(backupName)
{
    if(!inQuickon) return false;

    $('.origin-action').attr('disabled', 'disabled');
    loadTable();
    let intervalId = setInterval(function()
    {
        $.get($.createLink('system', 'ajaxGetBackupProgress', 'backupName=' + backupName.replace(/-/g, '_')), function(resp)
        {
            if(resp.status == 'completed' || resp.status.toLowerCase().includes('failed'))
            {
                loadCurrentPage();
                clearInterval(intervalId);
            }
        }, 'json');
    }, 2000);
};

window.restoreInProgress = function(backupName)
{
    if(!inQuickon) return false;

    $('.origin-action').attr('disabled', 'disabled');
    loadTable();
    let intervalId = setInterval(function()
    {
        $.get($.createLink('system', 'ajaxGetRestoreProgress', 'backupName=' + backupName.replace(/-/g, '_')), function(resp)
        {
            if(resp.status == 'completed' || resp.status.toLowerCase().includes('failed'))
            {
                loadCurrentPage();
                clearInterval(intervalId);
            }
        }, 'json');

    }, 2000);
};

window.deleteInProgress = function(backupName)
{
    if(!inQuickon) return false;

    loadTable();
    let intervalId = setInterval(function()
    {

        $.get($.createLink('system', 'ajaxGetDeleteProgress', 'backupName=' + backupName.replace(/-/g, '_')), function(resp)
        {
            if(resp.status == 'completed' || resp.status.toLowerCase().includes('failed'))
            {
                loadCurrentPage();
                clearInterval(intervalId);
            }
        }, 'json');

    }, 2000);
}

window.upgradeInProgress= function()
{
    if(!inQuickon) return false;

    loadTable();
    let intervalId = setInterval(function()
    {

        $.get($.createLink('system', 'ajaxGetUpgradeProgress'), function(resp)
        {
            if(resp.result == 'success')
            {
                loadCurrentPage();
                clearInterval(intervalId);
            }
        }, 'json');

    }, 2000);
}