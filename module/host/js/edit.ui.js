var params = 'id=' + host.id + '&osName={osName}&hostType={hostType}&isTestNode={isTestNode}';
window.osChange = function(e)
{
    var currentParam = params.replace('{osName}', e.target.value).replace('{hostType}', hostType).replace('{isTestNode}', isTestNode);
    loadPage($.createLink('host', 'edit', currentParam), '#osVersion');
}
window.hostTypeChange = function(e)
{
    var currentParam = params.replace('{osName}', osName).replace('{hostType}', e.target.value).replace('{isTestNode}', isTestNode);
    loadPage($.createLink('host', 'edit', currentParam), '#testType');
}

window.isTestNodeChange = function(e)
{
    const checked = $('#isTestNode_1').prop('checked');
    var currentParam = params.replace('{osName}', osName).replace('{hostType}', hostType).replace('{isTestNode}', checked ? 1 : 0);
    loadPage($.createLink('host', 'edit', currentParam), '#testContainer');
}
