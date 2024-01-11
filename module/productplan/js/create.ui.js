$(document).on('change', 'input[name=begin],input[name=end]', function()
{
    $("input[name='delta']").prop('checked', false);
});

window.clickSubmit = function()
{
    const parentPlan  = $('input[name=parent]').val();
    const branches    = $('select[name^=branch]').val().toString();
    const title       = $('input[name=title]').val();
    const begin       = $('input[name=begin]').val();
    const end         = $('input[name=end]').val();
    const parentBegin = typeof parentList[parentPlan] !== 'undefined' ? parentList[parentPlan]['begin'] : '';
    const parentEnd   = typeof parentList[parentPlan] !== 'undefined' ? parentList[parentPlan]['end'] : '';
    const errorBegin  = parentBegin && begin < parentBegin;
    const errorEnd    = parentEnd && end > parentEnd;

    if(parentPlan > 0 && branches !== '' && title && !errorBegin && !errorEnd)
    {
        const link = $.createLink('productplan', 'ajaxGetDiffBranchesTip', "productID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches);
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
