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
    }
    else if(col.name === 'name')
    {
        if(row.data.type == 'external')
        {
            result[0] = {html: '<a href="' + $.createLink('instance', 'view', 'id=' + row.data.externalID + '&type=external') + '">' + result[0] + '</a>'};
        }
        else
        {
            result[0] = {html: '<a href="' + $.createLink('instance', 'view', 'id=' + row.id) + '">' + result[0] + '</a>'};
        }
    }
    else if(col.name === 'createdAt')
    {
        if(value.includes('0000-00-00')) result[0] = '';
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
    if(appName == 'Nexus')
    {
        $('#editLinkContainer').attr('href', $.createLink('instance', 'editExternalApp', 'id=' + externalID));
    }
    else
    {
        $('#editLinkContainer').attr('href', $.createLink(appName.toLowerCase(), 'edit', 'id=' + externalID));
    }
    $('#editLinkContainer').trigger('click');
}
