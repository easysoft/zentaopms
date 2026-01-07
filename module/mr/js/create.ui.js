window.loadReviewers = function()
{
    const targetBranch = $('[name="targetBranch"]').val();
    const sourceBranch = $('[name="sourceBranch"]').val();
    const $reviewers   = $('[name^="reviewers"]');
    $.getJSON($.createLink('mr', 'ajaxGetReviewFlow', 'repoID=' + repoID + '&targetBranch=' + targetBranch), function(data)
    {
        if(data)
        {
            const flowID             = data.id;
            const defaultReviewers   = data.definition.reviewFlow.approvals.defaultReviewers;
            const specifiedReviewers = data.definition.reviewFlow.approvals.specifiedReviewers;
            const combined           = defaultReviewers.concat(specifiedReviewers);

            const reviewers = combined.filter((item, index) => {
                return combined.indexOf(item) === index;
            });
            $reviewers.zui('picker').$.setValue(reviewers.join(','));
            $('reviewFlowID').val(flowID);
        }
    });

    loadTarget($.createLink('mr', 'ajaxGetCreateCheckList', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
}
waitDom('[name=targetBranch]', loadReviewers);
