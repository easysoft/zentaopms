window.selectIcon = function()
{
    const icon = $(this).data('icon');
    $(this).closest('#iconPicker').find('#iconPreview').html("<i class='icon icon-" + icon + "'></i>");
    $(this).closest('#iconPicker').find('#icon').val(icon);
}
