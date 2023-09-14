window.afterPageUpdate = function($target, info, options)
{
    $('#addUnitBox').find("[name^='unit']").prop('disabled', true);
}
