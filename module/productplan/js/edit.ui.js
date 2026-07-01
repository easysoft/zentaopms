let isReverting = false;
window.branchChange = function()
{
    if(isReverting) return true;

    let newBranch = $('select[name^=branch]').val() ? $('select[name^=branch]').val().toString() : '';
    if(newBranch == oldBranch[planID]) return true;

    $.get($.createLink('productplan', 'ajaxGetConflict', `planID=${planID}&oldBranch=${oldBranch[planID]}&newBranch=${newBranch}`), function(conflictStories)
    {
        if(conflictStories != '')
        {
            zui.Modal.confirm({message: conflictStories, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(!res)
                {
                    isReverting = true;
                    const $branchPicker = $('select[name^=branch]').zui('picker');
                    $branchPicker.$.setValue(oldBranch[planID]);
                    isReverting = false;
                }
                else
                {
                    oldBranch[planID] = newBranch;
                }
            });
            return;
        }
        oldBranch[planID] = newBranch;
    });
}

$(document).on('change', 'input[name=begin],input[name=end]', function()
{
    $("input[name='delta']").prop('checked', false);
});

$(document).on('click', 'button[type=submit]', function()
{
    const parentPlan   = $('input[name=parent]').val();
    const branches     = $('[name^=branch]').val();
    const branchIdList = branches ? branches.toString() : '';
    const title        = $('input[name=title]').val();
    const begin        = $('input[name=begin]').val();
    const end          = $('input[name=end]').val();
    const parentBegin  = typeof parentList[parentPlan] !== 'undefined' ? parentList[parentPlan]['begin'] : '';
    const parentEnd    = typeof parentList[parentPlan] !== 'undefined' ? parentList[parentPlan]['end'] : '';
    const errorBegin   = parentBegin && begin && begin < parentBegin;
    const errorEnd     = parentEnd   && end   && end > parentEnd;
    if(parentPlan > 0 && branchIdList !== '' && title && !errorBegin && !errorEnd)
    {
        const link = $.createLink('productplan', 'ajaxGetDiffBranchesTip', "produtID=" + productID + "&parentID=" + parentPlan + "&branches=" + branchIdList);
        $.get(link, function(diffBranchesTip)
        {
            const $form    = $('#editForm').find('form');
            const formUrl  = $form.attr('action');
            const formData = new FormData($form[0]);
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
});
