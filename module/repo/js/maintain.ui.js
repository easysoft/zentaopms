window.renderActions = function(item, info)
{
    if(item.url)
    {
        if(typeof item.url == 'string') item.url = zui.formatString(item.url, info.row.data);
        else item.url.params = zui.formatString(item.url.params, info.row.data);
    }

    if(defaultServer && info.row.data.serviceHost == defaultServer)
    {
        item['data-confirm']['message'] = deleteConfirm;
        Object.assign(item, item);
        return item;
    }
    return item;
}
