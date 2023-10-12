$(document).on('change', 'input[name=begin],input[name=end]', function()
{
    $("input[name='delta']").prop('checked', false);
});

window.clickSubmit = function()
{
    const parentPlan = $('input[name=parent]').val();
    let branches     = $('[name^=branch]').val();
    if(parentPlan > 0 && branches)
    {
        const link = $.createLink('productplan', 'ajaxGetDiffBranchesTip', "productID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches.toString());
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
            else
            {
                $.ajaxSubmit({url: formUrl, data: formData});
            }
        });
        return false;
    }
}
