window.onRepoChange = function()
{
    const repoID = $('input[name="repoID"]').val();
    const link = $.createLink('repo', 'createBranch', linkParams.replace('%s', repoID));
    loadPage(link, {partial: true})
}
