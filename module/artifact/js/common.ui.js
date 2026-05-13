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
    if(!node)
    {
        if(typeof treeContext.window.loadTarget !== 'function')
        {
            if(retries > 0) setTimeout(function() { window.expandNode(node, retries - 1); }, 100);
            return;
        }

        const viewUrl = treeContext.window.location.href;
        treeContext.window.loadTarget(viewUrl, '#artifactViewTreeBlock', {selector: '#artifactViewTreeBlock>*'});
        return;
    }

    if(!treeApi || typeof treeApi.toggle !== 'function')
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
        setTimeout(function() { treeApi.toggle(keyPath, true); }, 100);
        return;
    }

    treeApi.toggle(keyPath, true);
}
