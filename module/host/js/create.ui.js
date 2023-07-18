window.osChange = function(e)
{
    loadPage($.createLink('host', 'create', 'osName=' + e.target.value), '#osVersion');
}