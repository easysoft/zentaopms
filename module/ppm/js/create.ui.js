window.loadReviewers = function()
{
    const targetBranch  = $('[name="targetBranch"]').val();
    const sourceBranch  = $('[name="sourceBranch"]').val();
    const canMerge      = $('[data-name=message]').data('canMerge');
    const conflictFiles = $('[data-name=message]').data('conflictFiles');

    if(!canMerge)
    {
        $('button[type=submit]').addClass('disabled');
        $('button[type=submit]').attr('disabled', 'disabled');
        if(conflictFiles.length > 0)
        {
            loadTarget($.createLink('ppm', 'ajaxGetConflictFiles', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
            $('#createCheckList').removeClass('hidden');
        }
        else
        {
            $('#createCheckList').addClass('hidden');
        }
    }
    else
    {
        $('button[type=submit]').removeClass('disabled');
        $('button[type=submit]').removeAttr('disabled');
        $('#createCheckList').removeClass('hidden');
        loadTarget($.createLink('ppm', 'ajaxGetCreateCheckList', 'repoID=' + repoID + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
    }
}

waitDom('[name=targetBranch]', loadReviewers);
