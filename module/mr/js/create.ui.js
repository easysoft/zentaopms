window.loadReviewers = function()
{
    const targetBranch = $('[name="targetBranch"]').val();
    const sourceBranch = $('[name="sourceBranch"]').val();
    const $reviewers   = $('[name^="reviewer"]');
    $.getJSON($.createLink('mr', 'ajaxGetReviewFlow', 'repoID=' + repoID + '&targetBranch=' + targetBranch), function(data)
    {
        if(data && typeof data.id != 'undefined')
        {
            const flowID             = data.id;
            const defaultReviewers   = data.definition.reviewFlow.approvals.defaultReviewers;
            const specifiedReviewers = data.definition.reviewFlow.approvals.specifiedReviewers;
            const combined           = defaultReviewers.concat(specifiedReviewers);

            const reviewers = combined.filter((item, index) => {
                return combined.indexOf(item) === index;
            });
            $reviewers.zui('picker').$.setValue(reviewers.join(','));
            $('#reviewFlowID').val(flowID);
        }
        else
        {
            $reviewers.zui('picker').$.setValue('');
            $('#reviewFlowID').val(0);
        }
    });

    $.getJSON($.createLink('mr', 'ajaxGetMergeCheckMessage', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), function(data)
    {
        if(data && typeof data.canMerge != 'undefined')
        {
            if(!data.canMerge)
            {
                $('#failMessage').removeClass('hidden');
                $('button[type=submit]').addClass('disabled');
                $('button[type=submit]').attr('disabled', 'disabled');
                $('[name=failMessage] span').text(data.message);
                $('#sourceSHA').val('');
                $('#mergeTargetSHA').val('');
                if(typeof data.conflictFiles != 'undefined' && data.conflictFiles.length > 0)
                {
                    $('#createCheckList').removeClass('hidden');
                    loadTarget($.createLink('mr', 'ajaxGetConflictFiles', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
                }
                else
                {
                    $('#createCheckList').addClass('hidden');
                }
            }
            else
            {
                $('#failMessage').addClass('hidden');
                $('button[type=submit]').removeClass('disabled');
                $('button[type=submit]').removeAttr('disabled');
                $('#createCheckList').removeClass('hidden');
                loadTarget($.createLink('mr', 'ajaxGetCreateCheckList', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
                $('#sourceSHA').val(data.sourceSHA);
                $('#mergeTargetSHA').val(data.targetSHA);
            }
        }
    });
}
waitDom('[name=targetBranch]', loadReviewers);
