window._setDocAppOptions = window.setDocAppOptions;
window.setDocAppOptions = function(_, options)
{
    options = window._setDocAppOptions(_, options);
    const onSwitchView = options.onSwitchView;
    return $.extend(options,
    {
        docFetcher: {url: options.docFetcher, dataFilter: (data) => $.extend(data, {lib: options.libID})},
        viewModeUrl: function(options)
        {
            const lib = this.getLib(options.libID);
            return $.createLink('doc', 'quick', zui.formatString('type={type}&docID={docID}&orderBy={orderBy}&recPerPage={recPerPage}&pageID={page}', $.extend({type: lib.data.quickType}, options))).replace('&docID=0&orderBy=&recPerPage=20&pageID=1', '').replace('&orderBy=&recPerPage=20&pageID=1', '');
        },
        onSwitchView: function(mode, location, info)
        {
            onSwitchView.call(this, mode, location, info);
            if(location.libID !== this.props.libID)
            {
                const url = $.createLink('doc', 'quick', `type=${this.lib.data.quickType}`);
                this.signals.loading.value = true;
                loadPartial(url, '#mainContent', {complete: () =>
                {
                    this.signals.loading.value = false;
                }});
            }
        },
        formatDataItem: function(type, item)
        {
            if(type === 'doc') item.libID = options.libID;
            return item;
        }
    });
};
