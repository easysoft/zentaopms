/**
 * 定义页面上的自定义渲染器。
 * Define the custom renderers on the page.
 */
const customRenders =
{
    /**
     * 落地页渲染，根据 API 接口类型显示不同的内容。
     * Home page render, show different content according to the API interface type.
     */
    home: function()
    {
        const docApp = this;
        const filterType = docApp.location.filterType || 'nolink';
        const pager = $.extend({page: 1, recPerPage: 20}, docApp.signals.pager.value);
        const params = docApp.signals.params.value || 'notempty_unclosed';
        const homeViewUrl = $.createLink('api', 'ajaxgethome', `type=${filterType}&params=${params}&recPerPage=${pager.recPerPage}&pageID=${pager.page}`);
        return {fetcher: homeViewUrl, clearBeforeLoad: false, className: 'doc-api-home h-full', class: 'h-full col',htmlRender: (element, props) => $(element).morphInner(`<div class="lazy-content doc-api-home h-full">${props.html}</div>`)};
    },

    /**
     * 定义 API 文档编辑器渲染，包括查看、编辑和创建。
     * Define the API doc editor render, including view, edit and create.
     */
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
        if(!docAppHasPriv('view')) return {html: `<h1>${doc ? doc.title : ''}</h1><p>${getDocAppLang('accessDenied')}</p>`};
        const release = this.signals.libReleaseMap.value[doc.lib] || 0;
        const version = this.signals.docVersion.value || 0;
        return {fetcher: $.createLink('api', 'view', `libID=${doc.lib}&apiID=${doc.id}&moduleID=${doc.module}&version=${version}&release=${release}`), loadingText: getDocAppLang('loadingDocsData'), className: 'doc-editor-content'};
    },

    /**
     * 定义 API 文档列表渲染，包括结构和版本。
     * Define the API doc list render, including structs and releases.
     */
    list: function()
    {
        const listType = this.signals.listType.value;
        const libID = this.libID;
        const releaseID = this.signals.libReleaseMap.value[libID] || 0;
        if(listType === 'structs') return {fetcher: $.createLink('api', 'struct', `libID=${this.libID}&releaseID=${releaseID}`), loadingText: getDocAppLang('loading'), className: 'api-struct-list'};
        if(listType === 'releases') return {fetcher: $.createLink('api', 'releases', `libID=${this.libID}`), loadingText: getDocAppLang('loading'), className: 'api-release-list'};
    },

    /**
     * 定义 API 文档列表筛选菜单渲染，当展示结构和版本时不显示筛选菜单。
     * Define the API doc list filter menu render, not show the filter menu when showing structs and releases.
     */
    filters: function()
    {
        const listType = this.signals.listType.value;
        if(this.mode !== 'list' || !listType) return;
        return null;
    },

    /**
     * 定义 API 文档列表工具栏渲染。
     * Define the API doc list toolbar render.
     */
    toolbar: function()
    {
        const listType = this.signals.listType.value;
        if(this.mode === 'list' && listType)
        {
            const items = [];
            if(docAppHasPriv('createStruct') && listType === 'structs') items.push({text: getDocAppLang('createStruct'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', 'data-size': 'lg', url: $.createLink('api', 'createStruct', `libID=${this.libID}`)});
            if(docAppHasPriv('createRelease') && listType === 'releases') items.push({text: getDocAppLang('createRelease'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', url: $.createLink('api', 'createRelease', `libID=${this.libID}`)});
            if(!items.length) return null;
            return {component: 'toolbar', props: {items: items}};
        }
    },

    /**
     * 定义顶部面包屑导航渲染。
     * Define the top breadcrumb render.
     */
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
                const options = [{text: getDocAppLang('latestVersion'), value: 0, selected: !release, command: `changeLibRelease/${libID}/0`}];
                versions.forEach(version => options.push({selected: release === version.id, text: `v${version.version}`, value: version.id, command: `changeLibRelease/${libID}/${version.id}`}));
                const currentVersion = versions.find(x => x.id === release);
                const versionPicker = zui.renderCustomContent(
                {
                    content:
                    {
                        component: 'DropdownButton',
                        props:
                        {
                            text     : currentVersion ? currentVersion.version : getDocAppLang('latestVersion'),
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

    /**
     * 定义侧边栏渲染，显示结构、版本和模块。
     * Define the sidebar render, show structs, releases and modules.
     */
    'sidebar-before': function()
    {
        if(!this.libID) return;

        const canViewStructs  = docAppHasPriv('struct');
        const canViewReleases = docAppHasPriv('releases');
        if(!canViewStructs && !canViewReleases) return;

        const isListMode = this.mode === 'list';
        const lang       = getDocAppLang();
        const listType   = this.signals.listType.value;
        const items      = [];
        if(canViewStructs) items.push({text: lang.struct, selected: listType === 'structs' && isListMode, icon: 'treemap muted', command: 'showStructs'}, {type: 'divider', className: 'my-1'});
        if(canViewReleases) items.push({text: lang.releases, selected: listType === 'releases' && isListMode, icon: 'version muted', command: 'showReleases'}, {type: 'divider', className: 'my-1'});
        items.push({text: lang.module, icon: 'list muted', command: 'showModules'});
        return {
            component: 'tree',
            className: 'p-2 pb-0 api-lib-menu',
            props: {items: items, itemProps: {className: 'state'}}
        };
    }
};

/**
 * 获取查看视图的 URL，用于更新浏览器地址栏。
 * Get the view mode URL for updating the browser address bar.
 */
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
        const params = zui.formatString('libID={libID}&moduleID={moduleID}&apiID={docID}&version={docVersion}&release={release}&browseType={filterType}&params={params}&orderBy={orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&mode={mode}&search={search}', options).replace('libID=0&moduleID=0&apiID=0&version=0&release=0&browseType=all&params=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '').replace('&apiID=0&version=0&release=0&browseType=all&params=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '').replace('&browseType=all&params=0&orderBy=order_asc&recTotal=0&recPerPage=20&pageID=1&mode=&search=', '');
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
    if(info.isNewDoc || info.mode === 'create') return [];
    return [
        {key: 'info',    icon: 'info',     title: lang.docInfo},
        info.mode === 'edit' ? null : {key: 'outline', icon: 'list-box', title: lang.docOutline},
        {key: 'history', icon: 'history',  title: lang.history},
    ].filter(Boolean);
}

function getSpaceFetcher(spaceType, spaceID)
{
    const parts      = String(spaceID).split('.');
    const objectType = parts[0] || 'nolink';
    const objectID   = parts[1] || 0;
    const libID      = getDocApp().libID;
    return $.createLink('api', 'ajaxGetDropMenu', `objectType=${objectType}&objectID=${objectID}&libID=${libID}`);
}

function handleClickSpaceMenu(event, value)
{
    event.preventDefault();
    if($(event.target).closest('.dropmenu-nav').length) return;
    const parts  = String(value).split('.');
    const type   = parts[0];
    const id     = parts[1] || 0;
    const docApp = getDocApp();
    if(type === 'product' || type === 'project') docApp.selectSpace(value, true, true);
    else                                         docApp.selectSpace('nolink', +id, true);
}

function getSpaceMenuText(text, state)
{
    const spaceID = this.spaceID;
    if(spaceID === 'nolink') return text;
    const libTypeList = getDocAppLang('libTypeList');
    return `${libTypeList[spaceID.split('.')[0]]} / ${text}`;
}

function getSpaceMenuOptions(spaceType, spaceID)
{
    const libID = this.libID;
    const lang = $('html').attr('lang');

    return {
        popWidth    : ['zh-cn', 'zh-tw'].includes(lang) ? 350 : 400,
        onClickItem : handleClickSpaceMenu,
        defaultValue: spaceID === 'nolink' ? `lib.${libID}`: spaceID,
        display     : getSpaceMenuText.bind(this)
    };
}

/* 扩展文档应用操作按钮生成定义。 Extend the doc app action definition. */
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

/* 扩展文档应用命令定义。 Extend the doc app command definition. */
$.extend(window.docAppCommands,
{
    /**
     * 保存 API 文档数据。
     * Save the API doc data.
     */
    saveApiDoc: function()
    {
        /* 触发 API 表单的提交事件。 */
        $('#docApp .doc-view form').trigger('submit');
    },

    /**
     * 取消编辑 API 文档库。
     * Create api lib.
     */
    createLib: function(_, args)
    {
        const docApp  = getDocApp();
        const spaceID = args[0] || docApp.spaceID;
        const parts   = String(spaceID).split('.');
        const url     = $.createLink('api', 'createLib', `type=${parts[0] || 'nolink'}&objectID=${parts[1] || 0}`);
        zui.Modal.open({size: 'sm', url: url});
    },

    /**
     * 加载指定的 API 文档。
     * Load the specified API doc.
     */
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

    /**
     * 选择 API 文档。
     * Select the specified API doc.
     */
    selectApi: function(_, args)
    {
        const apiID = args[0];
        getDocApp().selectDoc(apiID);
    },

    /**
     * 显示结构列表。
     * Show the struct list.
     */
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

    /**
     * 显示版本列表。
     * Show the release list.
     */
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

    /**
     * 显示API 目录。
     * Show the api catalog.
     */
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

    /**
     * 获取库的 API 列表。
     * Get the API list of the library.
     */
    loadLibApi: function(_, args)
    {
        const docApp        = getDocApp();
        const libReleaseMap = docApp.signals.libReleaseMap.value;
        const libID         = args[0] || docApp.libID;
        if(!libID) return;

        const releaseID = args[1] || libReleaseMap[libID] || 0;
        const url       = $.createLink('api', 'ajaxGetLibApiList', `libID=${libID}&releaseID=${releaseID}`);
        $.getJSON(url, function(data)
        {
            const lib = docApp.getLib(libID);
            if(lib && lib.docs && lib.docs.length) docApp.delete('doc', lib.docs.map(x => x.data.id));
            docApp.update('doc', data);
        });
    },

    /**
     * 更改当前库的发布版本。
     * Change the release of the current library.
     */
    changeLibRelease: function(_, args)
    {
        const docApp = getDocApp();
        const libID = args[0];
        const release = args[1];
        const libReleaseMap = docApp.signals.libReleaseMap.value;
        if(libReleaseMap[libID] === release) return;
        libReleaseMap[libID] = release;
        docApp.signals.libReleaseMap.value = $.extend({}, libReleaseMap);
        docApp.executeCommand('loadLibApi', [libID, release]);
        const doc = docApp.doc;
        if(doc && doc.data.lib === libID) docApp.executeCommand('loadApi', [doc.data.id, 0, release]);
    },

    /**
     * 加载懒加载内容。
     * Load the lazy content.
     */
    loadLazyContent: function(_, args)
    {
        const selector = args[0];
        if(!selector) return;
        $(selector).closest('.lazy-content').trigger('loadContent');
    },

    /**
     * 更新懒加载内容。
     * Update the lazy content.
     */
    updateLazyContent: function(context, args)
    {
        const event    = context.event;
        const $element = $(event.currentTarget);
        const selector = args[0] || $element.data('lazyTarget');
        const $lazy    = (selector ? $(selector) : $element).closest('.lazy-content');
        const url      = $element.data('url') || $element.attr('href');
        if(url) $lazy.trigger('loadContent', url);
        event.preventDefault();
        event.stopPropagation();
    },

    /**
     * 更新首页内容。
     * Update the home content.
     */
    loadHome: function(context, args)
    {
        const event      = context.event;
        const $element   = $(event.currentTarget);
        const data       = $element.data();
        const type       = args[0] !== undefined ? args[0] : data.type;
        const params     = args[1] !== undefined ? args[1] : data.params;
        const recPerPage = args[2] !== undefined ? args[2] : data.recPerPage;
        const pageID     = args[3] !== undefined ? args[3] : data.pageID;
        window.loadHome(type, params, recPerPage, pageID);
    },

    /**
     * 显示首页菜单。
     * Show the home item menu.
     */
    showHomeItemMenu: function(context, args)
    {
        const event = context.event;
        const type  = args[0];
        const id    = args[1];
        const lang  = getDocAppLang();
        const items = [];

        if(type === 'nolink')
        {
            if(docAppHasPriv('editLib')) items.push({text: lang.actions.editLib, command: `editLib/${id}`});
            if(docAppHasPriv('deleteLib')) items.push({text: lang.actions.deleteLib, command: `deleteLib/${id}`});
        }
        else if(docAppHasPriv('createLib'))
        {
            items.push({text: lang.createLib, command: `createLib/${type}.${id}`});
        }

        if(items.length)
        {
            zui.ContextMenu.show({event: event, items: items, placement: 'bottom-end'});
        }
        event.preventDefault();
        event.stopPropagation();
    },
});

window.loadHome = function(type, params, recPerPage, pageID)
{
    const location = {};
    if(typeof type === 'object') $.extend(location, type);
    else if(type !== undefined) location.filterType = type;
    if(params !== undefined) location.params = params;
    if(recPerPage !== undefined || pageID !== undefined)
    {
        location.pager = {};
        if(recPerPage !== undefined) location.pager.recPerPage = recPerPage;
        if(pageID !== undefined)     location.pager.page = pageID;
    }
    if(Object.keys(location).length) getDocApp().switchView(location, 'home');
};

/**
 * 重写文档应用的配置选项方法。
 * Override the method to set the doc app options.
 */
window._setDocAppOptions = window.setDocAppOptions; // Save the original method.
window.setDocAppOptions = function(_, options) // Override the method.
{
    options = window._setDocAppOptions(_, options);
    const oldIsMatchFilter = options.isMatchFilter; // Save the original isMatchFilter method.
    return $.extend(options,
    {
        defaultState         : {libReleaseMap: {}, listType: ''},
        spaceMenuOptions     : getSpaceMenuOptions,
        customRenders        : customRenders,
        viewModeUrl          : getViewModeUrl,
        getDocViewSidebarTabs: getDocViewSidebarTabs,
        getSpaceFetcher      : getSpaceFetcher,
        isMatchFilter        : function(type, filterType, item)
        {
            if(type === 'api') return (item.objectType || 'nolink').toLowerCase() === filterType.toLowerCase();
            return oldIsMatchFilter.call(this, type, filterType, item);
        }
    });
};
