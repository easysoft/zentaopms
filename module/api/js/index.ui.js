const customRenders =
{
    editor: function()
    {
        const mode = this.mode;
        if(mode === 'create')
        {
            const libID = this.libID;
            const moduleID = this.moduleID;
            return {fetcher: $.createLink('api', 'create', `libID=${libID}&moduleID=${moduleID}&space=api`), loadingText: getDocAppLang('loading')};
        }
        if(mode === 'edit')
        {
            const doc = this.doc.data;
            return {fetcher: $.createLink('api', 'edit', `apiID=${doc.id}`), loadingText: getDocAppLang('loading')};
        }
        const doc = this.doc.data;
        const release = this.signals.libReleaseMap.value[doc.lib] || 0;
        const version = this.signals.docVersion.value || 0;
        return {fetcher: $.createLink('api', 'view', `libID=${doc.lib}&apiID=${doc.id}&moduleID=${doc.module}&version=${version}&release=${release}`), loadingText: getDocAppLang('loadingDocsData'), className: 'doc-editor-content'};
    },
    list: function()
    {
        const listType = this.signals.listType.value;
        const libID = this.libID;
        const releaseID = this.signals.libReleaseMap.value[libID] || 0;
        if(listType === 'structs') return {fetcher: $.createLink('api', 'struct', `libID=${this.libID}&releaseID=${releaseID}`), loadingText: getDocAppLang('loading'), className: 'api-struct-list'};
        if(listType === 'releases') return {fetcher: $.createLink('api', 'releases', `libID=${this.libID}`), loadingText: getDocAppLang('loading'), className: 'api-release-list'};
    },
    filters: function()
    {
        const listType = this.signals.listType.value;
        if(this.mode !== 'list' || !listType) return;
        return null;
    },
    toolbar: function()
    {
        const listType = this.signals.listType.value;
        if(this.mode === 'list' && listType)
        {
            const items = [];
            if(listType === 'structs') items.push({text: getDocAppLang('createStruct'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', 'data-size': 'lg', url: $.createLink('api', 'createStruct', `libID=${this.libID}`)});
            if(listType === 'releases') items.push({text: getDocAppLang('createRelease'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', url: $.createLink('api', 'createRelease', `libID=${this.libID}`)});
            return {component: 'toolbar', props: {items: items}};
        }
    },
    'app-nav': function(items)
    {
        const lib = this.lib;
        if(!lib) return items;
        const versions = lib.data.versions;
        if(versions && versions.length)
        {
            const viewIndex = items.findIndex(item => item[0] === 'lib');
            if(viewIndex >= 0)
            {
                const libView = items[viewIndex][1];
                const libID   = lib.data.id;
                const release = this.signals.libReleaseMap.value[libID] || 0;
                const options = [{text: getDocAppLang('defaultVersion'), value: 0, selected: !release, command: `changeLibRelease/${libID}/0`}];
                versions.forEach(version => options.push({selected: release === version.id, text: `v${version.version}`, value: version.id, command: `changeLibRelease/${libID}/${version.version}`}));
                const currentVersion = versions.find(x => x.id === release);
                const versionPicker = zui.renderCustomContent(
                {
                    content:
                    {
                        component: 'DropdownButton',
                        props:
                        {
                            text     : currentVersion ? currentVersion.version : getDocAppLang('version'),
                            size     : 'xs',
                            type     : 'gray-pale',
                            rounded  : 'full',
                            className: 'h-4 gap-1 mr-2',
                            items    : options,
                        }
                    }
                });
                items[viewIndex] = ['lib', [libView, versionPicker]];
            }
        }
        const listType = this.signals.listType.value;
        if(this.mode !== 'list' || !listType) return items;
        if(listType === 'structs')  items.push([listType, zui.renderCustomContent({className: 'mx-2', content: getDocAppLang('struct')})]);
        if(listType === 'releases') items.push([listType, zui.renderCustomContent({className: 'mx-2', content: getDocAppLang('releases')})]);
        return items;
    },
    'sidebar-before': function()
    {
        if(!this.libID) return;
        const isListMode = this.mode === 'list';
        const lang = getDocAppLang();
        const listType = this.signals.listType.value;
        const items = [
            {text: lang.struct, selected: listType === 'structs' && isListMode, icon: 'treemap muted', command: 'showStructs'},
            {type: 'divider', className: 'my-1'},
            {text: lang.releases, selected: listType === 'releases' && isListMode, icon: 'version muted', command: 'showReleases'},
            {type: 'divider', className: 'my-1'},
            {text: lang.module, icon: 'list muted', command: 'showModules'}
        ];
        return {
            component: 'tree',
            className: 'p-2 pb-0 api-lib-menu',
            props: {items: items, itemProps: {className: 'state'}}
        };
    }
};

function getViewModeUrl(options)
{
    const doc = this.doc;
    options = $.extend({release: (doc ? this.signals.libReleaseMap.value[doc.lib] : 0) || 0}, options);
    let url;
    if(this.mode === 'view')
    {
        const params = zui.formatString('libID={libID}&apiID={docID}&moduleID={moduleID}&version={docVersion}&release={release}', options).replace('&version=0&release=0', '');
        url = $.createLink('api', 'view', params);
    }
    else
    {
        const params = zui.formatString('libID={libID}&moduleID={moduleID}&apiID={docID}&version={docVersion}&release={release}&browseType={filterType}&param=0&orderBy={orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&mode={mode}&search={search}', options).replace('libID=0&moduleID=0&apiID=0&version=0&release=0&browseType=all&param=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '').replace('&apiID=0&version=0&release=0&browseType=all&param=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '').replace('&browseType=all&param=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '');
        url = $.createLink('api', 'index', params);
    }
    return url;
}

/**
 * 获取文档详情侧边栏标签页定义。
 * Get the doc view sidebar tabs.
 *
 * @param {object} doc
 */
function getDocViewSidebarTabs(doc, info)
{
    const lang = getDocAppLang();
    if(info.isNewDoc) return [{key: 'outline', icon: 'list-box', title: lang.docOutline}];
    return [
        {key: 'info',    icon: 'info',     title: lang.docInfo},
        {key: 'outline', icon: 'list-box', title: lang.docOutline},
        {key: 'history', icon: 'history',  title: lang.history},
    ];
}

/**
 * 获取文档界面上的表格初始化选项。
 * Get the table initialization options on the doc UI.
 *
 * @param {object} options
 * @param {object} info
 * @returns {object}
 */
function getTableOptions(options, info)
{
    return $.extend(options, {
        cols: [{name: 'title', onRenderCell: function(result, info)
        {
            const doc = info.row.data;
            return [{className: `api-list-item row items-center my-0.5 mx-1 gap-2 flex-auto is-${doc.method} cursor-pointer rounded`, html: [
                `<div class="font-mono w-14 text-center api-method py-1 rounded rounded-r-none">${doc.method}</div>`,
                `<div class="font-mono api-path">${doc.path}</div>`,
                `<div class="flex-auto text-right api-title pr-2">${doc.originTitle}</div>`,
            ].join('')}]
        }}],
        header:    false,
        checkable: false,
        footer:    ['flex', 'pager'],
    });
}

$.extend(window.docAppActions,
{
    /**
     * 定义文档编辑时的操作按钮。
     * Define the actions on toolbar of the doc editing page.
     */
    'doc-edit': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getDocAppLang();
        return [
            {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
        ];
    },
    /**
     * 定义文档创建时的操作按钮。
     * Define the actions on toolbar of the doc editing page.
     */
    'doc-create': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getDocAppLang();
        return [
            {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
        ];
    },
});

$.extend(window.docAppCommands,
{
    saveApiDoc: function()
    {
        $('#docApp .doc-view form').trigger('submit');
    },
    loadApi: function(_, args)
    {
        const apiID   = args[0] || this.docID;
        const version = args[1] || 0;
        const release = args[2] || 0;
        const select  = !!args[3];
        $.getJSON($.createLink('api', 'ajaxGetApi', `apiID=${apiID}&version=${version}&release=${release}`), function(result)
        {
            if(!result || typeof result !== 'object') return;
            const docApp = getDocApp();
            docApp.update('doc', result);
            if(select) docApp.selectDoc(apiID);
        });
    },
    showStructs: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: 'structs',
        });
    },
    showReleases: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: 'releases',
        });
    },
    showModules: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: '',
        });
    },
    changeLibRelease: function(_, args)
    {
        const docApp = getDocApp();
        const libID = args[0];
        const release = args[1];
        const libReleaseMap = docApp.signals.libReleaseMap.value;
        libReleaseMap[libID] = release;
        docApp.signals.libReleaseMap.value = $.extend({}, libReleaseMap);
        const doc = docApp.doc;
        if(doc && doc.data.lib === libID) docApp.executeCommand('loadApi', [doc.data.id, 0, release]);
    },
    loadLazyContent: function(_, args)
    {
        const selector = args[0];
        if(!selector) return;
        $(selector).closest('.lazy-content').trigger('loadContent');
    },
    updateLazyContent: function(context, args)
    {
        const event = context.event;
        const $element = $(event.currentTarget);
        const selector = args[0] || $element.data('lazyTarget');
        const $lazy = (selector ? $(selector) : $element).closest('.lazy-content');
        const url = $element.data('url') || $element.attr('href');
        if(url) $lazy.trigger('loadContent', url);
        event.preventDefault();
        event.stopPropagation();
    }
});

window._setDocAppOptions = window.setDocAppOptions;
window.setDocAppOptions = function(_, options)
{
    options = window._setDocAppOptions(_, options);
    const oldIsMatchFilter = options.isMatchFilter;
    return $.extend(options,
    {
        defaultState         : {libReleaseMap: {}, listType: ''},
        customRenders        : customRenders,
        viewModeUrl          : getViewModeUrl,
        getTableOptions      : getTableOptions,
        getDocViewSidebarTabs: getDocViewSidebarTabs,
        isMatchFilter        : function(type, filterType, item)
        {
            if(type === 'api') return (item.objectType || 'nolink').toLowerCase() === filterType.toLowerCase();
            return oldIsMatchFilter.call(this, type, filterType, item);
        }
    });
};
