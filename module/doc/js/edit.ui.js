window.beforeRequestContent = function(options)
{
    if(!options.isDiffPage || !$('#docForm').hasClass('has-changed')) return;

    return zui.Modal.confirm($('#docForm').data('unsavedConfirm'));
};
