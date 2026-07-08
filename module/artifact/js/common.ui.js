window.expandNode = function(node, retries)
{
    retries = retries === undefined ? 10 : retries;

    const candidates = [window];
    if(window.parent && window.parent !== window) candidates.push(window.parent);
    if(window.parent && window.parent.parent && window.parent.parent !== window.parent) candidates.push(window.parent.parent);

    const visited = [];
    let treeContext = null;

    candidates.some(function(candidate)
    {
        if(!candidate || visited.indexOf(candidate) !== -1) return false;
        visited.push(candidate);

        let $ = null;
        try
        {
            $ = candidate.jQuery || candidate.$;
        }
        catch(error)
        {
            return false;
        }

        if(!$ || !$.fn || typeof $.fn.zui !== 'function') return false;

        const $tree = $('.filesTree');
        if(!$tree.length) return false;

        treeContext = {window: candidate, $: $, $tree: $tree, tree: $tree.zui('tree')};
        return true;
    });

    if(!treeContext) return;

    const $tree = treeContext.$tree;
    const tree  = treeContext.tree;
    const treeApi = tree && tree.$;
    const viewUrl = $tree.attr('data-refresh-url') || treeContext.window.location.href;
    const loadTarget = treeContext.window.loadTarget;
    const refreshTarget = function(target, selector)
    {
        loadTarget(viewUrl, target, {selector: selector});
    };

    if(!node)
    {
        if(typeof loadTarget !== 'function')
        {
            if(retries > 0) setTimeout(function() { window.expandNode(node, retries - 1); }, 100);
            return;
        }

        refreshTarget('#artifactViewTreeBlock', '#artifactViewTreeBlock>*');
        refreshTarget('#artifactViewPage', '#artifactViewPage>*');
        return;
    }

    if(typeof loadTarget !== 'function' || !treeApi || typeof treeApi.toggle !== 'function')
    {
        if(retries > 0) setTimeout(function() { window.expandNode(node, retries - 1); }, 100);
        return;
    }

    const $node = $tree.find(`[z-key="${node}"]`).first();
    if(!$node.length) return;

    const keyPath = $node.attr('z-key-path') || node;
    const expanded = typeof treeApi.isExpanded === 'function' ? treeApi.isExpanded(keyPath) : $node.hasClass('is-nested-show');

    if(expanded)
    {
        treeApi.toggle(keyPath, false);
        setTimeout(function()
        {
            treeApi.toggle(keyPath, true);
            refreshTarget('#artifactViewPage', '#artifactViewPage>*');
        }, 100);
        return;
    }

    treeApi.toggle(keyPath, true);
    refreshTarget('#artifactViewPage', '#artifactViewPage>*');
}

window.parentPickerRequestToken = 0;

window.toggleArtifactBatchDelete = function()
{
    const checkedList = this && typeof this.getChecks === 'function' ? this.getChecks() : [];
    $('.artifact-batch-delete').toggleClass('hidden', checkedList.length === 0);
};

window.batchDeleteArtifact = function(event)
{
    const dtable = zui.DTable.query(event.target);
    if(!dtable || !dtable.$) return;

    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let $button = $(event.target);
    if(!$button.hasClass('batch-btn')) $button = $button.closest('.batch-btn');

    const postData = new FormData();
    checkedList.forEach(function(id) {postData.append('assetIDList[]', id);});

    zui.Modal.confirm({message: $button.data('confirm'), icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(function(res)
    {
        if(res) $.ajaxSubmit({url: $button.data('url'), data: postData});
    });
};

window.loadParents = function(retries)
{
    retries = retries === undefined ? 10 : retries;

    const $artifactField  = $('[name=artifactID]');
    const $artifactPicker = $artifactField.zui('picker');
    const artifactID      = $artifactPicker && $artifactPicker.$ ? $artifactPicker.$.state.value : $artifactField.val();

    if(!artifactID)
    {
        if(retries > 0) setTimeout(function() { window.loadParents(retries - 1); }, 100);
        return;
    }

    const $parentField      = $('[name=parent]');
    const $parentPicker     = $parentField.zui('picker');
    const $parentLoadingDom = $parentField.closest('.form-group').find('.picker-box').first();
    let defaultParentPath   = artifactID == currentArtifactID ? (currentParentPath || '/') : '/';
    const link              = $.createLink('artifact', 'ajaxGetDirParentItems', 'artifactID=' + artifactID + '&path=');
    const requestToken      = ++window.parentPickerRequestToken;

    if(window.disableRootParent && defaultParentPath === '/') defaultParentPath = '';

    if($parentPicker) $parentPicker.render({disabled: true});
    if($parentLoadingDom.length) toggleLoading($parentLoadingDom, true);

    $.getJSON(link)
        .done(function(items)
        {
            if(requestToken !== window.parentPickerRequestToken || !$parentPicker) return;

            if(window.disableRootParent && items && items.length)
            {
                items.forEach(function(item)
                {
                    if(String(item.value) === '/') item.disabled = true;
                });
            }

            $parentPicker.render({items: items, disabled: false});
            $parentPicker.$.setValue(defaultParentPath);
        })
        .always(function()
        {
            if(requestToken !== window.parentPickerRequestToken) return;

            if($parentPicker) $parentPicker.render({disabled: false});
            if($parentLoadingDom.length) toggleLoading($parentLoadingDom, false);
        });
};

window.showCommand = function(command, title)
{
    const content = `<div class="input-group">
        <input class="form-control docker-url" readonly type="text" value="${command}"> </input>
        <span class="input-group-addon cursor-pointer" onclick="copyCommand()">
        <i class="icon icon-copy"></i>
        </span>`;
    zui.Modal.open({type:'custom', title: title, content: {html: content}});
};

window.copyCommand = function()
{
    $('.docker-url')[0].select();
    document.execCommand('copy');
    window.getSelection().removeAllRanges();

    zui.Messager.show({type: 'success', content: copyMessage, time: 2000});
};
