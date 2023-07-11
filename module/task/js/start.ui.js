/* If left = 0, warning. */
$('button[type="submit"]').on('click', function()
{
    var left     = parseFloat($("#left").val());
    var consumed = parseFloat($("#consumed").val());
    if(!left)
    {
        if(!consumed)
        {
            zui.Modal.alert(noticeTaskStart);
            return false;
        }
        else
        {
            const formUrl  = $('#startForm').attr('action');
            const formData = new FormData($("#startForm")[0]);
            zui.Modal.confirm(confirmFinish).then((res) => {
                if(res) $.ajaxSubmit({url: formUrl, data: formData})
            });

            return false;
        }
    }
})
