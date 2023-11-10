$(function()
{
    $.get($.createLink('artifactrepo', 'ajaxUpdateArtifactRepos'), function(response)
    {
        data = JSON.parse(response);
        if(data.hasUpdate) loadTable(pageLink);
    });
});

window.renderList = function (result, {col, row, value})
{
    if(col.name === 'status')
    {
        switch(value)
        {
            case 'online':
                var statusClass = 'text-success';
                break;
            case 'offline':
                var statusClass = 'text-danger';
                break;
            default:
                var statusClass = '';
        }
        result[0] = {html: '<span class="' + statusClass + '">' + result[0] + '</span>'};
    }

    return result;
}
