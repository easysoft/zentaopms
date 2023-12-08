var repoData;

/**
 * Get artifactreos.
 *
 * @param  event $event
 * @access public
 * @return void
 */
function getArtifactRepo(event)
{
    const server = $(event.target).val();
    const url    = $.createLink('artifactrepo', 'ajaxGetArtifactRepos', 'serverID=' + server);
    if(!server) return;

    toggleLoading('#repoName', true);
    $.get(url, function(response)
    {
        repoData = JSON.parse(response);

        if(repoData.result !== undefined && repoData.result === 'fail')
        {
            zui.Modal.alert(repoData.message);
            toggleLoading('#repoName', false);
            return;
        }
        var repoItems = [];
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
 * @return void
 */
function onRepoChange()
{
    var repoName = $('[name=repoName]').val();
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
    $('div.servers .form-label').addClass('required');
});

