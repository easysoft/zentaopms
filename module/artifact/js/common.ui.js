window.expandNode = function(node, retries)
{
    const $tree = $('.filesTree');
    if(!node || !$tree.length || typeof $tree.zui !== 'function') return;

    const tree = $tree.zui('tree');
    const treeApi = tree && tree.$;
    if(!treeApi || typeof treeApi.toggle !== 'function')
    {
        retries = retries === undefined ? 10 : retries;
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
