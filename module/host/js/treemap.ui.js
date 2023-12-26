window.onTreemapNodeClick = function(node)
{
    if(node.hostid) loadModal($.createLink('host', 'view', 'hostID=' + node.hostid));
}
