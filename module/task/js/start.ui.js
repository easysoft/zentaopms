/* If left = 0, warning. */
window.clickSubmit = function()
{
    var left     = parseFloat($("#left").val());
    var consumed = parseFloat($("#consumed").val());
    if(!left && consumed)
    {
        const formUrl    = $('#startForm form').attr('action');
        const formData   = new FormData($("#startForm form")[0]);
        const confirmTip = currentTeam ? confirmTeamFinish : confirmFinish;
        zui.Modal.confirm(confirmTip).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });

        return false;
    }
}
