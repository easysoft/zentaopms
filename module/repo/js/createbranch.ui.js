window.onRepoChange = function()
{
    toggleLoading('#branchFrom', true);
    $('#branchCreateForm [type=submit]').addClass('disabled');

    const repoID   = $('input[name="codeRepo"]').val();
    const $fromDom = $('[name=branchFrom]').zui('picker');
    $fromDom.$.clear();

    $.getJSON($.createLink('repo', 'ajaxGetBranchesAndTags', `repoID=${repoID}`), function(data)
    {
        const items    = []
        let   selected = '';
        if(data.branches)
        {
            items.push({text: branchLang, items: [], disabled: true, key: undefined});

            for(const branch in data.branches)
            {
                if(!selected) selected = branch;
                items[0].items.push({'text': branch, 'value': branch});
            }

            if(!selected) delete items[0];
        }

        if(data.tags)
        {
            items.push({text: tagLang, items: [], disabled: true, key: undefined});

            const index = items.length - 1;
            for(const tag in data.tags)
            {
                if(!selected) selected = tag;
                items[index].items.push({'text': tag, 'value': tag});
            }

            if(items[index].items.length == 0) delete items[index];
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

const onHostChange = window.onHostChange

window.onHostChange = function()
{
    onHostChange();

    const host = $('[name=serviceHost]').val();
    $.get($.createLink('repo', 'ajaxGetServiceInfo', "serviceID=" + host), function(resp)
    {
        if(resp)
        {
            $('#committer').removeClass('hidden');
            $('[name=namespace]').closest('.form-group').addClass('hidden');
            $('[name=namespace]').zui('picker').$.clear();
        }
        else
        {
            $('#committer').addClass('hidden');
            $('[name^=committer]').zui('picker').$.clear();
            $('[name=namespace]').closest('.form-group').removeClass('hidden');
        }
    });
}
