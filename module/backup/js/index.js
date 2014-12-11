$(function()
{
    $('.backup').click(function()
    {
        $('#waitting .modal-body #backupType').html(backup);
        $('#waitting').modal('show');
    })
    $('.restore').click(function()
    {
        $('#waitting .modal-body #backupType').html(restore);
        $('#waitting').modal('show');
    })
})
