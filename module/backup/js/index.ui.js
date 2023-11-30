window.backup = function(e)
{
    var timeID = null;
    $.ajaxSubmit({
        url: $(e).data('link'),
        onComplete: function(data)
        {
            clearInterval(timeID);
            $('#waiting .modal-body #message').append(data);

            setTimeout(function()
            {
                loadPage($.createLink('backup', 'index'));
            }, 2000);
        },
    });

    $('#waiting .modal-body #backupType').html(backupLang);

    zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
    $('#waiting').addClass('show');

    timeID = setInterval(function()
    {
        $.get($.createLink('backup', 'ajaxGetProgress'), function(data)
        {
            $('#waiting .modal-content #message').html(data);
        });
    }, 1000);
};

window.getCellSpan = function(cell)
{
    if((cell.col.name == 'time' || cell.col.name == 'actions') && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
};

window.restore = function(name)
{
    zui.Modal.confirm({message: confirmDeleteLang, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res)
        {
            $.ajaxSubmit({url: $.createLink('backup', 'restore', 'file=' + name)});

            zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
            $('#waiting').addClass('show');
            $('#waiting .modal-body #backupType').html(restoreLang);
            $('#waiting .modal-body #message').empty();
        }
    });
};

$(document).off('click', '.rmPHPHeader').on('click', '.rmPHPHeader', function(data)
{
    if($(this).hasClass('disabled')) return false;

    zui.Modal.open({id: 'waiting', size: 'sm', backdrop: false});
    $('#waiting').addClass('show');
    $('#waiting .modal-body #backupType').html(rmPHPHeaderLang);
    $('#waiting .modal-body #message').empty();
});
