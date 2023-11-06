window.renderCell = function(result, {col, row})
{
    if(col.name === 'full_path')
    {
        result[0] = {html:'<a href="' + gitlabUrl + '/' + row.data.full_path + '" target="_blank">' + row.data.full_path + '</a>', style:{flexDirection:"column"}};
        return result;
    }

    return result;
};

function searchGroup()
{
    loadPage({method:'post', data: {keyword: $('#keyword').val()}, target: '#table-gitlab-browsegroup>*'});
}
