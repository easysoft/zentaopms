window.renderCell = function(result, info)
{
    const space = info.row.data;
    if(info.col.name == 'name' && result)
    {
        if(typeof result[0] != 'object') return result;

        const repoID = spaceRepoMap[space.id] ? spaceRepoMap[space.id] : 0;
        if(repoID) result[0].props.href = $.createLink('repo', 'browse', `repoID=${repoID}`);
        return result;
    }
    return result;
}
