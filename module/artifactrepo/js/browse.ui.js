$(function()
{
    $.getJSON($.createLink('artifactrepo', 'ajaxUpdateArtifactRepos'), function(data)
    {
        if(data.hasUpdate) reloadPage();
    });
});

window.renderList = function (result, {col, value})
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
