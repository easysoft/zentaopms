window.renderCell = function(result, {col, row})
{
    if(col.name === 'star')
    {
        result[0] = {html:'<span title="' + projectStar + '" ><i class="icon icon-star"></i>' + row.data.star_count + '</span><span title="' + projectFork + '" class="ml-1"><i class="icon icon-code-fork"></i>' + row.data.forks_count + '</span>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};

function searchProject()
{
    loadPage({method:'post', data: {keyword: $('#keyword').val()}, target: '#table-gitlab-browseproject>*'});
}
