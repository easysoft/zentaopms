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
    $('.dtable-cell-content .toolbar button.disabled.browseImage').attr('title', uninitNotice);
    $('.dtable-cell-content .toolbar button.disabled.ajax-submit').attr('title', undeletedNotice);
}

if(showFeature)
{
    var encodedHelpPageUrl = 'https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao';
    window.open(encodedHelpPageUrl);
}
