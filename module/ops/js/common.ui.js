$('#opsForm').on('click', '.icon-plus', function(e)
{
    $(this).parent().parent().after(template);
});

$('#opsForm').on('click', '.icon-close', function(e)
{
    $(this).parent().parent().remove();
});