window.osChange = function(e)
{
    loadPage($.createLink('host', 'edit', 'id=' + host.id + '&osName=' + e.target.value), '#osVersion');
}