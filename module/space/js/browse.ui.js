window.renderInstanceList = function (result, {col, row, value})
{
    if(col.name === 'status')
    {
        switch(value)
        {
            case 'running':
                var statusClass = 'text-success';
                break;
            case 'abnormal':
                var statusClass = 'text-danger';
                break;
            default:
                var statusClass = '';
        }
        result[0] = {html: '<span class="' + statusClass + '">' + result[0] + '</span>'};
        return result;
    }
    else if(col.name === 'name')
    {
        if(row.data.externalID)
        {
            if(row.data.appName == 'Gitea') result[0] = {html: '<a href="' + $.createLink(row.data.appName, 'view', 'id=' + row.data.externalID) + '" data-toggle="modal">' + result[0] + '</a>'};
        }
        else
        {
            result[0] = {html: '<a href="' + $.createLink('instance', 'view', 'id=' + row.id) + '">' + result[0] + '</a>'};
        }
        return result;
    }

    return result;
}
