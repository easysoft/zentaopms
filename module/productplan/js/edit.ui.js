$(document).on('change', 'select[name^=branch]', function()
{
    let newBranch = $('select[name^=branch]').val() ? $('select[name^=branch]').val().toString() : '';
    $.get($.createLink('productplan', 'ajaxGetConflict', 'planID=' + planID + '&newBranch=' + newBranch), function(conflictStories)
    {
        if(conflictStories != '')
        {
            zui.Modal.confirm(conflictStories).then((res) => {
                if(!res)
                {
                    const $branchPicker = $('select[name^=branch]').zui('picker');
                    $branchPicker.$.setValue(oldBranch[planID].split(','));
                }
            });
        }
    });
});

$(document).on('change', 'input[name=begin],input[name=end]', function()
{
    $("input[name='delta']").prop('checked', false);
});

$(document).on('click', 'button[type=submit]', function()
{
    const parentPlan = $('input[name=parent]').val();
    let branches     = $('[name^=branch]').val();
    if(parentPlan > 0 && branches)
    {
        const link = $.createLink('productplan', 'ajaxGetDiffBranchesTip', "produtID=" + productID + "&parentID=" + parentPlan + "&branches=" + branches.toString());
        $.get(link, function(diffBranchesTip)
        {
            const formUrl  = $('#editForm').attr('action');
            const formData = new FormData($("#editForm")[0]);
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
