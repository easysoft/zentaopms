window.onRepoChange = function()
{
    const repoID = $('input[name="codeRepo"]').val();
    const link = $.createLink(module, 'createBranch', linkParams.replace('%s', repoID));
    loadModal(link, 'current');
}
