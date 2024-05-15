window.onRepoChange = function()
{
    toggleLoading('#branchFrom', true);
    $('#branchCreateForm [type=submit]').addClass('disabled');

    const repoID   = $('input[name="codeRepo"]').val();
    const $fromDom = $('[name=branchFrom]').zui('picker');
    const items    = $fromDom.$.props.items;
    items[0].items = [];
    items[1].items = [];
    $fromDom.$.clear();

    $.getJSON($.createLink('repo', 'ajaxGetBranchesAndTags', `repoID=${repoID}`), function(data)
    {
        let selected = '';
        if(data.branches)
        {
            for(const branch in data.branches)
            {
                if(!selected) selected = branch;
                items[0].items.push({'text': branch, 'value': branch});
            }
        }

        if(data.tags)
        {
            for(const tag in data.tags)
            {
                if(!selected) selected = tag;
                items[1].items.push({'text': tag, 'value': tag});
            }
        }
        $fromDom.render({items: items});
        $fromDom.$.setValue(selected);
        toggleLoading('#branchFrom', false);
        $('#branchCreateForm [type=submit]').removeClass('disabled');
    });
}

$(document).on('click', '.modal-actions > button', function()
{
    loadCurrentPage();
})
