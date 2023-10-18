window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
};

window.renderList = function (result, {col, row, value})
{
    if(col.name === 'status')
    {
        switch(value)
        {
            case 'wait':
                var statusClass = 'text-danger';
                break;
            default:
                var statusClass = '';
                break;
        }
        result[0] = {html: '<span class="' + statusClass + '">' + result[0] + '</span>'};
    }

    return result;
}

window.afterRender = function()
{
}

function goHelp()
{
    var encodedHelpPageUrl = encodeURIComponent('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao');
    var urlForNewTab = webRoot + '#app=help&url=' + encodedHelpPageUrl;
    window.open(urlForNewTab)
}
