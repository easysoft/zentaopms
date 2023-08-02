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
            if(row.data.appName == 'Gitea' || row.data.appName == 'Gitlab') result[0] = {html: '<a href="' + $.createLink(row.data.appName, 'view', 'id=' + row.data.externalID) + '" data-toggle="modal">' + result[0] + '</a>'};
        }
        else
        {
            result[0] = {html: '<a href="' + $.createLink('instance', 'view', 'id=' + row.id) + '">' + result[0] + '</a>'};
        }
        return result;
    }

    return result;
}

var timer = null;
window.afterPageUpdate = function()
{
    if(timer) return;
    const postData = new FormData();
    idList.forEach(function(id)
    {
        postData.append('idList[]', id)
    });
    timer = setInterval(function()
    {
        $.ajaxSubmit({
            url: $.createLink('instance', 'ajaxStatus'),
            method: 'POST',
            data:postData,
            onComplete: function(res)
            {
                if(res.result != 'success') return;
                $.each(res.data, function(index, instance)
                {
                    if(statusMap[instance.id] != instance.status)
                    {
                        clearInterval(timer);
                        statusMap[instance.id] = instance.status;
                        loadPage();
                    }
                });
            }
        });
    }, 10000);
}

window.onPageUnmount = function()
{
    if(timer == null) return;
    clearInterval(timer);
}

window.bindUser = function(externalID, appName)
{
    openUrl($.createLink(appName.toLowerCase(), 'bindUser', 'id=' + externalID));
}

window.editApp = function(externalID, appName)
{
    $('#editLinkContainer').attr('href', $.createLink(appName.toLowerCase(), 'edit', 'id=' + externalID));
    $('#editLinkContainer').trigger('click');
}
