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

function getObjectBrowseUrl(object, objectType, libID)
{
    const browsePath =
    {
        execution: ['execution', 'browse'],
        project: ['doc', 'projectSpace'],
        product: ['doc', 'productSpace'],
        mine: ['doc', 'mySpace'],
        custom: ['doc', 'teamSpace'],
    };
    const path = browsePath[objectType];
    return path ? $.createLink(path[0], path[1], `objectID=${object.id}&libID=${libID || 0}`) : null;
}

const buildDocActions = window.docAppActions.doc;
window.docAppActions.doc = function(info)
{
    const actions = buildDocActions(info);
    if(info.ui === 'toolbar')
    {
        const doc       = info.data;
        const object    = doc.object;
        const lib       = doc.libInfo;
        const objectUrl = object ? getObjectBrowseUrl(object, doc.objectType) : null;
        const libUrl    = object ? getObjectBrowseUrl(object, doc.objectType, lib.id) : null;
        actions.unshift(
        {
            type: 'custom',
            component: 'div',
            className: 'order-first mr-2',
            html: `<span class="text-gray">${getDocAppLang('position')}${getDocAppLang('colon')} </span>` + [
                objectUrl ? `<a href="${objectUrl}">${object.name || object.title}</a>` : null,
                lib ? `<a href="${libUrl}">${lib.name}</a> ` : null,
            ].filter(Boolean).join(' <span class="text-gray">/</span> '),
        });
    }
    return actions;
};
