let repoData;

/**
 * Get artifactreos.
 *
 * @param  event $event
 * @access public
 * @return viod
 */
window.getArtifactRepo = function()
{
    let server = $('[name=serverID]').val();
    if(!server) server = serverID;
    if(!server) return;

    toggleLoading('#repoName', true);
    $.getJSON($.createLink('artifactrepo', 'ajaxGetArtifactRepos', 'serverID=' + server), function(response)
    {
        repoData = response;
        if(repoData.result !== undefined && repoData.result === 'fail')
        {
            zui.Modal.alert(repoData.message);
            toggleLoading('#repoName', false);
            return;
        }

        const repoItems = [];
        for(i in repoData)
        {
            repoItems.push({'text': repoData[i].name, 'value': repoData[i].name});
        }
        $artifactRepo = $('#repoName').zui('picker');
        $artifactRepo.render({items: repoItems});
        $artifactRepo.$.clear();
        toggleLoading('#repoName', false);
    });
}

/**
 * Repo change event.
 *
 * @access public
 * @return viod
 */
window.onRepoChange = function()
{
    const repoName = $('[name=repoName]').val();
    if(!repoName)
    {
        $('#type').val('');
        $('#format').val('');
        $('#status').val('');
        $('#url').val('');
        return;
    }

    for(i in repoData)
    {
        if(repoData[i].name == repoName)
        {
            $('#type').val(repoData[i].type);
            $('#format').val(repoData[i].format);
            $('#status').val(repoData[i].online ? 'online' : 'offline');
            $('#url').val(repoData[i].url);
        }
    }
}

$(function()
{
    window.getArtifactRepo();
});
