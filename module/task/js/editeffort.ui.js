window.clickSubmit = function()
{
    const isEmpty = !$('#editEffortForm [name=date]').val() || !$('#editEffortForm [name=consumed]').val();
    const left    = $('#editEffortForm [name=left]').val();
    if(!isEmpty && !isReadonly && (left == 0 || !left))
    {
        const formUrl  = $('#editEffortForm form').attr('action');
        const formData = new FormData($("#editEffortForm form")[0]);
        zui.Modal.confirm(finishTaskTip).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });
        return false;
    }
}
