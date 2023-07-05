$(document).on('change', '#begin, #end', function()
{
    $("input[name='delta']").prop('checked', false);
});

$(document).on('click', 'button[type=submit]', function()
{
    const parentPlan = $('#parent').val();
    let branches     = $('#branch').val();
    if(parentPlan > 0 && branches)
    {
        const link = $.createLink('productplan', 'ajaxGetDiffBranchesTip', "produtID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches.toString());
        $.get(link, function(diffBranchesTip)
        {
            const formUrl  = $('#createForm').attr('action');
            const formData = new FormData($("#createForm")[0]);
            if(diffBranchesTip != '')
            {
                zui.Modal.confirm(diffBranchesTip).then((res) => {
                    if(res) $.ajaxSubmit({url: formUrl, data: formData})
                });
            }
        });
        return false;
    }
});
