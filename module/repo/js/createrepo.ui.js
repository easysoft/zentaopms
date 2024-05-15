window.onHostChange = function()
{
    const host = $('[name=serviceHost]').val();
    if(!host) return false;

    const $groups = $('#namespace').zui('picker');
    toggleLoading('#namespace', true);
    $.get($.createLink('repo', 'ajaxGetGroups', "host=" + host), function(resp)
    {
        resp = JSON.parse(resp);
        $groups.render({items: resp.options});
        $groups.$.clear();
        toggleLoading('#namespace', false);
        $('.hide-service').toggle(resp.server.type == 'gitea');
    });
}
