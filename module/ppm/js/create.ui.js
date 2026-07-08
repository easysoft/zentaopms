function safe64Encode(value)
{
    return btoa(unescape(encodeURIComponent(value))).replace(/\//g, '.');
}

window.loadReviewers = function()
{
    var targetBranch  = $('[name="targetBranch"]').val();
    var sourceBranch  = $('[name="sourceBranch"]').val();
    const canMerge      = $('[data-name=message]').data('canMerge');
    const conflictFiles = $('[data-name=message]').data('conflictFiles');
    var repo = $('[name="repoID"]').val() ? $('[name="repoID"]').val() : repoID;

    var targetBranch = safe64Encode(targetBranch);
    var sourceBranch = safe64Encode(sourceBranch);

    if(!canMerge)
    {
        $('button[type=submit]').addClass('disabled');
        $('button[type=submit]').attr('disabled', 'disabled');
        if(conflictFiles.length > 0)
        {
            loadTarget($.createLink('ppm', 'ajaxGetConflictFiles', 'repoID=' + repo + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
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
        loadTarget($.createLink('ppm', 'ajaxGetCreateCheckList', 'repoID=' + repo + '&sourceBranch=' + sourceBranch + '&targetBranch=' + targetBranch), 'createCheckList');
    }
}

waitDom('[name=targetBranch]', loadReviewers);
