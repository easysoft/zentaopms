/* If left = 0 or consumed < recordedConsumed, warning. */
window.clickSubmit = function()
{
    let left     = parseFloat($("#left").val());
    let consumed = parseFloat($("#consumed").val());

    if(consumed < recordedConsumed)
    {
        const $consumed = $('#restartForm #consumed');
        $('#restartForm #consumedTip').remove();
        $consumed.closest('.input-control').after(`<div class='form-tip text-danger' id='consumedTip'>${consumedSmallError}</div>`);
        $consumed.addClass('has-error').trigger('focus');
        return false;
    }
    if(!left && consumed)
    {
        const formUrl    = $('#restartForm form').attr('action');
        const formData   = new FormData($("#restartForm form")[0]);
        const confirmTip = currentTeam ? confirmTeamFinish : confirmFinish;
        zui.Modal.confirm(confirmTip).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });

        return false;
    }
}
