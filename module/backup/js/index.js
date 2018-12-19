$(function()
{
    $('.backup').click(function()
    {
        $('#waitting .modal-body #backupType').html(backup);
        $('#waitting').modal('show');
        setInterval(function()
        {
            $.get(createLink('backup', 'ajaxGetProgress'), function(data)
            {
                $('#waitting .modal-content #message').html(data);
            });
        }, 1000);
    })
    $('.rmPHPHeader').click(function()
    {
        $('#waitting .modal-body #backupType').html(rmPHPHeader);
        $('#waitting .modal-content #message').hide();
        $('#waitting').modal('show');
    })

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
        })

        return false;
    })
})
