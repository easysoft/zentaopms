window.backup = function(e)
{
    var timeID = null;
    $.ajaxSubmit({
        url: $(e).data('link'),
        onComplete: function(data)
        {
            clearInterval(timeID);

            $('#waitting .modal-body #message').append(data);

            setTimeout(function()
            {
                return loadCurrentPage();
            }, 2000);
        },
    });

    $('#waitting .modal-body #backupType').html(backup);
    zui.Modal.open({id: 'waitting', size: 'sm', backdrop: false, show: true});

    timeID = setInterval(function()
    {
        $.get($.createLink('backup', 'ajaxGetProgress'), function(data)
        {
            $('#waitting .modal-content #message').html(data);
        });
    }, 1000);
}
