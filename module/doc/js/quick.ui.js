window._setDocAppOptions = window.setDocAppOptions;
window.setDocAppOptions = function(_, options)
{
    options = window._setDocAppOptions(_, options);
    const onSwitchView = options.onSwitchView;
    const initLibID = options.libID;
    options = $.extend(options,
    {
        docFetcher: {url: options.docFetcher, dataFilter: (data) => $.extend(data, {lib: initLibID || options.libID})},
        viewModeUrl: function(options)
        {
            const lib       = this.getLib(options.libID);
            const quickType = lib ? lib.data.quickType : 'view';
            return $.createLink('doc', 'quick', zui.formatString('type={type}&docID={docID}&orderBy={orderBy}&recPerPage={recPerPage}&pageID={page}', $.extend({type: quickType}, options))).replace('&docID=0&orderBy=&recPerPage=20&pageID=1', '').replace('&orderBy=&recPerPage=20&pageID=1', '');
        },
        onSwitchView: function(mode, location, info)
        {
            onSwitchView.call(this, mode, location, info);
            trySwitchView(this, location.libID, mode);
        },
        formatDataItem: function(type, item)
        {
            if(type === 'doc') item.libID = options.libID;
            return item;
        }
    });
    if(typeof window._currentLibID === 'number') options.libID = window._currentLibID;
    return options;
};

/**
 * 尝试切换视图。
 * Try to switch view.
 * @param {Object} docApp - 文档应用实例。
 * @param {number} libID - 库ID。
 * @param {string} mode - 模式。
 */
function trySwitchView(docApp, libID, mode)
{
    if(window._trySwitchViewTimer) clearTimeout(window._trySwitchViewTimer);
    if(libID === window._currentLibID || mode !== 'list') return;

    window._currentLibID = libID;
    window._trySwitchViewTimer = setTimeout(() => {
        window._trySwitchViewTimer = 0;
        if(libID === docApp.props.libID) return;

        const lib       = docApp.lib;
        const quickType = lib ? lib.data.quickType : 'view';
        const url       = $.createLink('doc', 'quick', `type=${quickType}`);
        docApp.signals.loading.value = true;
        $('#mainContent').addClass('loading');
        loadPartial(url, '#mainContent', {complete: () => {
            $('#mainContent').removeClass('loading');
            docApp.signals.loading.value = false;
        }});
    }, 10);
}

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
    const actions = buildDocActions.call(this, info);
    if(info.ui === 'toolbar')
    {
        const doc    = info.data;
        const object = doc.object;
        const lib    = doc.libInfo;
        const docUrl = $.createLink('doc', 'view', `docID=${doc.id}`);
        actions.unshift(
        {
            type: 'custom',
            component: 'div',
            className: 'order-first mr-2',
            html: [
                `<span class="text-gray">${getDocAppLang('position')}${getDocAppLang('colon')} </span>`,
                `<a href="${docUrl}">`,
                    [
                        object ? (object.name || object.title) : '',
                        lib ? lib.name : '',
                    ].filter(Boolean).join(' <span class="text-gray">/</span> '),
                '</a>',
            ].join(''),
        });
    }
    return actions;
};

window.docAppCommands.exportWord = function(_, args)
{
    const docApp   = getDocApp();
    const docID    = args[2] || docApp.docID;
    const doc      = docID ? docApp.getDoc(docID) : null;
    if(!doc || !doc.objectType) return;

    const moduleID = args[1] || docApp.moduleID;
    const libID = doc.libInfo ? doc.libInfo.id : (args[0] || docApp.libID);
    const type  = doc.objectType || doc.object.type;
    const url   = $.createLink('doc', `${type}2export`, `libID=${libID}&moduleID=${moduleID}&docID=${docID}`);
    window.open(url, '_self');
};

window.onPageUnmount = function()
{
    window._currentLibID = null;
};
