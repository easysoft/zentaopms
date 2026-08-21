window.onRepoChange = function()
{
    toggleLoading('#tagBranch', true);
    $('#tagCreateForm [type=submit]').addClass('disabled');

    const repoID   = $('input[name="codeRepo"]').val();
    const $fromDom = $('[name=tagBranch]').zui('picker');
    $fromDom.$.clear();

    $.getJSON($.createLink('repo', 'ajaxGetBranchesAndTags', `repoID=${repoID}`), function(data)
    {
        const items    = []
        let   selected = '';
        if(data.branches)
        {
            items.push({text: branchLang, items: [], disabled: true, key: undefined});

            for(const tag in data.branches)
            {
                if(!selected) selected = tag;
                items[0].items.push({'text': tag, 'value': tag});
            }

            if(!selected) delete items[0];
        }

        $fromDom.render({items: items});
        $fromDom.$.setValue(selected);
        toggleLoading('#tagBranch', false);
        $('#tagCreateForm [type=submit]').removeClass('disabled');
    });
}

window.onBranchChange = function ()
{
    let repoID     = $('input[name="codeRepo"]').val();
    const branchID = $('input[name="tagBranch"]').val();
    const $fromDom = $('[name=tagFrom]').zui('picker');
    if(typeof repoID == 'undefined')
    {
        repoID = $('input[name="devopsCodeRepo"]').val();
    }
    $fromDom.$.clear();
    $fromDom.render({items: $.createLink('repo', 'ajaxGetBranchCommits', `repoID=${repoID}&branchID=${branchID}&search={search}`)});
    toggleLoading('#tagFrom', false);
}

$(document).on('click', '.modal-actions > button', function()
{
    loadCurrentPage();
})
