window.clickSubmit = function(e)
{
    if($(e.submitter).hasClass('save-btn'))   $('input[name=status]').val('normal');
    if($(e.submitter).hasClass('save-draft')) $('input[name=status]').val('draft');
}

window.saveDoc = function()
{
    const formData = new FormData($('#docEditForm')[0]);
    $.ajaxSubmit({
        url: $('#docEditForm').attr('action'),
        data: formData,
        onSuccess: () => {
            zui.Modal.query('#modalBasicInfo').hide();
            return false;
        }
    });
}
