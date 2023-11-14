window.onTreemapNodeClick = function(node)
{
    loadModal($.createLink('host', 'view', 'hostID=' + node.hostid));
}
