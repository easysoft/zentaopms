window.onRepoChange = function()
{
    toggleLoading('#branchFrom', true);
    $('#branchCreateForm [type=submit]').addClass('disabled');
    const repoID   = $('input[name="codeRepo"]').val();
    const $fromDom = $('[name=branchFrom]').zui('picker');
    $fromDom.$.clear();
    $.getJSON($.createLink('repo', 'ajaxGetBranchesAndTags', `repoID=${repoID}`), function(data)
    {
        const branches = [];
        if(data.branches)
        {
            for(const branch in data.branches)
            {
                branches.push({'text': branch, 'value': branch});
            }
        }
        $fromDom.render({items: branches});
        $fromDom.$.setValue(branches.length > 0 ? branches[0].value : '');
        toggleLoading('#branchFrom', false);
        $('#branchCreateForm [type=submit]').removeClass('disabled');
    });
}
