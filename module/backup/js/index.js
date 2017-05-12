$(function()
{
    $('.backup').click(function()
    {
        $('#waitting .modal-body #backupType').html(backup);
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
