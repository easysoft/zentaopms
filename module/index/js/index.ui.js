/* Init variables */
const apps =
{
    /** @type {Record<string, ZentaoApp>} */
    map: {},
    /** @type {Record<string, ZentaoOpenedApp>} */
    openedMap: {},
    defaultCode: '',
    zIndex: 10,
    frameContent: null,
    theme: null,
    oldPages: new Set(oldPages)
};

const DEBUG = config.debug;

function getUrlID(url)
{
    const info = $.parseLink(url);
    return info.moduleName ? `${info.moduleName}-${info.methodName}` : '';
}

function showLog(code, name, moreTitles, trace, moreInfos)
{
    const titles = ['%c HOME '];
    const titleColors = ['color:#fff;font-weight:bold;background:#009688'];
    if(code)
    {
        titles.push(`%c ${code} `);
        titleColors.push('background:rgba(0,150,136,0.2);color:#009688;');
    }
    if(name)
    {
        titles.push(`%c ${name} `);
        titleColors.push('color:#009688;font-weight:bold;');
    }
    if(!Array.isArray(moreTitles)) moreTitles = [moreTitles];
    if(typeof moreTitles[0] === 'string' && (moreTitles[0].startsWith('success:') || moreTitles[0].startsWith('error:')))
    {
        const message = moreTitles.shift();
        const [type, content] = message.split(':', 2);
        titles.push(`%c ${content} `);
        titleColors.push(`color:${type === 'error' ? '#f56c6c' : '#67c23a'};`);
    }
    if(trace || moreInfos)
    {
        console.groupCollapsed(titles.join(''), ...titleColors, ...moreTitles);
        if(trace) console.trace(trace);
        if(moreInfos)
        {
            if($.isPlainObject(moreInfos)) Object.keys(moreInfos).forEach((infoName) => console.log(infoName, moreInfos[infoName]));
            else console.log(moreInfos);
        }
        console.groupEnd();
    }
    else
    {
        console.log(titles.join(''), ...titleColors, ...moreTitles);
    }
}

function triggerAppEvent(code, event, args, options)
{
    const app = apps.openedMap[code];
    if(!app) return;

    event = event.includes('.') ? event : `${event}.apps`;
    if(DEBUG && (!options || options.silent !== true)) showLog(code, 'Event', event, {args});
    if(!Array.isArray(args)) args = [args];
    if(app.$app) app.$app.trigger(event, args);
    try
    {
        const iframeWindow = app.iframe && app.iframe.contentWindow;
        if(iframeWindow) return iframeWindow.$(iframeWindow.document).trigger(event, args);
    }
    catch(e){}
}

function isOldPage(url)
{
    if(typeof url !== 'object') url = $.parseLink(url);
    return apps.oldPages.has(url.moduleName.toLowerCase()) || apps.oldPages.has(`${url.moduleName}-${url.methodName}`.toLowerCase());
}

/**
 * 提取纯文本标签（去除 HTML 标签）
 * @param {string} html
 * @returns {string}
 */
function extractLabelText(html)
{
    return html.replace(/<[^>]*>/g, '').trim();
}

/**
 * 获取所有应用列表。
 * @returns {Array<{id: string, label: string, active: boolean}>}
 */
function getApps()
{
    const lastAppCode = getLastAppCode();
    return Array.from(allAppsItemsMap.values()).map(item => ({
        id: item.code,
        label: extractLabelText(item.title),
        active: lastAppCode === item.code
    }));
}

/**
 * 获取所有可见的应用列表。
 * @returns {Array<{id: string, label: string, active: boolean}>}
 */
function getVisibleApps()
{
    const $mainNav = $('#menuMainNav');
    return getApps().filter(app => {
        const $menuItem = $mainNav.find(`li[data-app="${app.id}"]`);
        return $menuItem.length > 0 && !$menuItem.is('[data-hidden="1"]');
    });
}

/**
 * 获取所有已打开的应用列表。
 * @returns {Array<{id: string, label: string, active: boolean}>}
 */
function getOpenedApps()
{
    const lastAppCode = getLastAppCode();
    return Object.values(apps.openedMap).map(app => ({
        id: app.code,
        label: extractLabelText(app.title),
        active: app.code === lastAppCode
    }));
}

/**
 * 触发 onChangeApp 回调函数。
 */
function changeApp()
{
    const callback = window.zinCallbacks.onChangeApp;
    if(typeof callback === 'function')
    {
        try { callback(getVisibleApps(), getOpenedApps()); } catch (e) { console.error('[ZIN] onChangeApp callback error:', e); }
    }
}

/**
 * Open app
 * @param {string} url
 * @param {string|object} [code]
 * @param {boolean|object} [options]
 * @returns {ZentaoOpenedApp|undefined}
 */
function openApp(url, code, options)
{
    if(!apps.frameContent)
    {
        setTimeout(() => {openApp(url, code, options);}, 100);
        return;
    }
    const loadOptions = {};
    if(typeof code === 'object') $.extend(loadOptions, code);
    else if(code) loadOptions.code = code;
    if(typeof options === 'boolean') loadOptions.forceReload = options;
    else if(typeof options === 'object') $.extend(loadOptions, options);
    options = loadOptions;
    code = options.code;
    let forceReload = options.forceReload !== false;
    delete options.forceReload;
    delete options.code;

    if(!code)
    {
        if(apps.map[url])
        {
            code = url;
            url  = '';
        }
        else if(url)
        {
            url  = url.replace('http:', window.location.protocol).replace('https:', window.location.protocol);
            code = getAppCode(url);
        }
        if(!code) return openApp('my');
    }

    const app = apps.map[code];
    if(!app)
    {
        if(DEBUG) showLog(code, 'APP NOT FOUND!', url, options);
        return zui.Modal.alert(appNotFound);
    }

    /* Create iframe for app */
    let openedApp = apps.openedMap[code];
    if(!openedApp)
    {
        if(!url) url = app.url;
        openedApp = $.extend({opened: true, url: url, zIndex: ++apps.zIndex, currentUrl: url}, app);
        forceReload = false;
        apps.openedMap[code] = openedApp;

        const $iframe =
        $([
            '<iframe',
                'id="appIframe-' + code + '"',
                'name="app-' + code + '"',
                'class="fade"',
                'allowfullscreen="true"',
                app.external ? 'src="' + url + '"' : '',
                'frameborder="no"',
                'allowtransparency="true"',
                'scrolling="auto"',
            '/>'
        ].join(' '));
        const iframe = $iframe[0];
        openedApp.iframe = iframe;
        openedApp.$app = $('<div class="app-container loading" id="app-' + code + '"></div>')
            .append($iframe)
            .appendTo('#apps');

        const finishLoad = () =>
        {
            $iframe.removeClass('loading').addClass('in');
            setTimeout(() => {openedApp.$app.removeClass('loading');}, 300);
        };
        if(app.external)
        {
            $iframe.on('ready.app', () =>{openApp(url, code, options);});
        }
        else
        {
            const writeToDoc = () => {
                iframe.contentDocument.open();
                const html = apps.frameContent.replace('window.defaultAppUrl = ""', `window.defaultAppUrl = "${url}"`);
                iframe.contentDocument.write(html);
                iframe.contentDocument.close();
            };
            if(!iframe.contentDocument.body.children.length) setTimeout(() => writeToDoc(), 500);
        }
        iframe.onload = iframe.onreadystatechange = function(e)
        {
            try
            {
                iframe.contentWindow.$(iframe.contentDocument).one('pageload.app pagecaheload.app', finishLoad);
                setTimeout(finishLoad, 10000);
            }
            catch(e){finishLoad()}
            triggerAppEvent(openedApp.code, 'loadapp', [openedApp, e]);
        };
        openedApp.$app.show().css('z-index', openedApp.zIndex);
    }
    if(!url) url = openedApp.currentUrl;

    /* Set tab cookie */
    $.cookie.set('tab', code, {expires: config.cookieLife, path: config.webRoot});

    /* Highlight on left menu */
    const $menuNav  = $('#menuMainNav,#menuMoreNav');
    const $lastItem = $menuNav.find('li>a.active');
    if($lastItem.data('app') !== code)
    {
        $lastItem.removeClass('active');
        $menuNav.find('li[data-app="' + code + '"]>a').addClass('active');
        openedApp.$app.trigger('showapp', openedApp);
    }

    /* Show and load app */
    const isSameUrl = $.parseLink(openedApp.currentUrl).url === $.parseLink(url).url;
    const needLoad = !isSameUrl || forceReload !== false;
    if(needLoad)
    {
        const openFromHidden = openedApp.zIndex < apps.zIndex;
        reloadApp(code, url, openFromHidden ? $.extend({loadingIndicatorDelay: '0s'}, options) : options);
        openedApp.$app.toggleClass('open-from-hidden', openFromHidden);
    }
    else
    {
        updateApp(code, url, openedApp.currentTitle, 'show');
    }

    $('body').attr('data-app', code);
    openedApp.zIndex = ++apps.zIndex;
    openedApp.$app.show().css('z-index', openedApp.zIndex);
    openedApp.getPageInfo = () => {
        const getPageInfo = openedApp.iframe.contentWindow.getPageInfo;
        return getPageInfo ? getPageInfo() : null;
    };
    openedApp.getPerfData = () => {
        const getPerfData = openedApp.iframe.contentWindow.getPerfData;
        return getPerfData ? getPerfData() : null;
    };

    /* Update on app tabs bar */
    const $tabs = $('#appTabs');
    let $tabItem  = $('#appTab-' + code);
    if(!$tabItem.length)
    {
        if (app.text === undefined) return false;
        const $link= $('<a data-pos="tab"></a>')
            .attr('data-app', code)
            .addClass('show-in-app')
            .append($('<span class="text"></span>').text(app.text));
        $tabItem = $('<li class="nav-item"></li>')
            .attr({'data-app': code, id: 'appTab-' + code})
            .append($link)
            .appendTo($tabs);
        openedApp.$bar = $tabItem;
    }
    const $lastTab = $tabs.find('li>a.active');
    if($lastTab.data('app') !== code)
    {
        $lastTab.removeClass('active');
        $tabs.find('li[data-app="' + code + '"]>a').addClass('active');
    }

    if(DEBUG) showLog(code, 'Open', getUrlID(url), openedApp, {options, forceReload, needLoad});
    triggerAppEvent(code, 'openapp', [openedApp, {load: needLoad}]);

    changeApp();

    return openedApp;
}

/**
 * Show app
 * @param {string} code
 */
function showApp(code)
{
    return openApp('', code, false);
}

/**
 * Reload app
 * @param {string} code
 * @param {string} url
 * @param {object} options
 */
function reloadApp(code, url, options)
{
    const app = apps.openedMap[code];
    if(!app) return;

    if(url === true) url = app.url;
    else if(!url) url = app.currentUrl;

    const iframe = app.iframe;
    try
    {
        if(app.external) iframe.src = url;
        else if(iframe.contentWindow.loadPage) iframe.contentWindow.loadPage(url, $.extend({updateHeading: true}, options));
        else if(DEBUG) showLog(code, 'Reload', 'error:Cannot load page when iframe is not ready.', {options});
        else console.error('[APPS]', 'Reload failed: Cannot load page when iframe is not ready.');
    }
    catch(error)
    {
        iframe.src = url;
    }

    app.currentUrl = url;

    changeApp();
}

function updateApp(code, url, title, type)
{
    const app = apps.openedMap[code];
    if(!app || app.external) return;

    const state     = typeof code === 'object' ? code : {code: code, url: url, title: title, type: type};
    const prevState = window.history.state;
    const urlInfo   = $.parseLink(state.url);

    state.prev  = prevState;
    state.index = prevState ? prevState.index + 1 : 1;
    state.path  = urlInfo.methodName ? `${urlInfo.moduleName}-${urlInfo.methodName}` : '';

    if(title)
    {
        document.title   = title;
        app.currentTitle = title;
    }

    if(prevState && prevState.code === code && prevState.url === url) return prevState;

    app.currentUrl = url;
    const urlParts = url.split('#');
    const hash = urlParts.length > 1 ? urlParts[1] : '';
    const browserUrl = getAppCode(urlParts[0]) !== code ? `${urlParts[0]}#${hash.length ? `${hash}&` : ''}app=${code}` : url;
    window.history.pushState(state, title, browserUrl);
    zui.store.session.set('lastOpenApp', {code, url});
    triggerAppEvent(code, 'updateapp', [code, url, title, type]);
    if(DEBUG) showLog(code, 'Update', title || getUrlID(url), state, {url, title, type});
    return state;
}

/**
 * Get last opened app
 * @returns {object} The opened app info object
 */
function getLastApp()
{
    let lastShowIndex = 0;
    let lastApp = null;
    $.each(apps.openedMap, function(_code, app)
    {
        if(lastShowIndex < app.zIndex && !app.closed)
        {
            lastShowIndex = app.zIndex;
            lastApp = app;
        }
    });
    return lastApp;
}

/** Get last opened app code. */
function getLastAppCode()
{
    const lastApp = getLastApp();
    return lastApp ? lastApp.code : null;
}

/**
 * Close app
 * @param {string} code
 * @returns {ZentaoOpenedApp|undefined|false}
 */
function closeApp(code)
{
    code = code || getLastAppCode();
    const app = apps.openedMap[code];
    if(!app) return;

    const iframe = app.iframe;
    if(iframe)
    {
        if(iframe && iframe.contentDocument && iframe.contentWindow && iframe.contentWindow.onCloseApp)
        {
            var result = iframe.contentWindow.onCloseApp();
            if(result === false) return false;
        }
    }

    $('#appTabs a.active[data-app="' + code + '"]').parent().remove();

    app.closed = true;
    app.$app.trigger('hideapp', app);
    app.$app.remove();
    app.$bar.remove();

    hideApp(code);
    delete apps.openedMap[code];

    triggerAppEvent(code, 'closeapp', app);
    return app;
}

/**
 * Hide app
 * @param {string} code
 * @returns {ZentaoOpenedApp|undefined}
 */
function hideApp(code)
{
    code = code || getLastAppCode();
    const app = apps.openedMap[code];
    if(!app) return;

    $('#menuNav a.active[data-app="' + code + '"]').removeClass('active');

    if(!app.closed) triggerAppEvent(code, 'hideapp', app);

    app.$app.hide();

    /* Active last app */
    const lastApp = getLastApp(true) || getLastApp();
    showApp(lastApp ? lastApp.code : apps.defaultCode);
    return app;
}

/**
 * Get app code from url
 * @param {String} urlOrModuleName Url string
 * @return {String}
 */
function getAppCode(urlOrModuleName, defaultCode)
{
    var code = navGroup[urlOrModuleName];
    if(code) return code;

    var link = $.parseLink(urlOrModuleName);
    if(!link.moduleName || link.isOnlyBody || (link.moduleName === 'index' && link.methodName === 'index')) return '';

    if(link.hash && link.hash.indexOf('app=') === 0) return link.hash.substr(4);

    /* Handling special situations */
    var moduleName        = link.moduleName;
    var methodName        = link.methodName;
    var moduleMethodLower = (moduleName + '-' + methodName).toLowerCase();

    if(moduleMethodLower === 'index-index') return 'my';
    if(moduleMethodLower === 'search-buildindex') return 'admin';

    if(moduleName === 'tutorial' && methodName === 'wizard')
    {
        moduleName = link.vars[0][1];
        methodName = link.vars[1][1];
    }

    var methodLowerCase = methodName.toLowerCase();
    if(moduleName === 'doc')
    {
        if(link.prj) return 'project';

        if((link.params.from || link.params.$3) == 'product')
        {
            if(['objectlibs', 'showfiles', 'browse', 'view', 'edit', 'delete', 'create'].includes(methodLowerCase)) return 'product';
        }
        return 'doc';
    }
    if(['caselib', 'testreport', 'testsuite', 'testtask', 'testcase', 'bug', 'qa'].includes(moduleName))
    {
        return link.prj ? 'project' : 'qa';
    }
    if(moduleName === 'report')
    {
        if(['usereport', 'editreport', 'deletereport', 'custom'].includes(methodLowerCase) && link.params.from) return 'system';
        if(methodName == 'projectsummary') return 'project';
        return 'bi';
    }
    if(moduleName === 'story' && vision === 'lite') return 'project'
    if(moduleName === 'testcase' && methodLowerCase === 'zerocase')
    {
        return link.params.from == 'project' ? 'project' : 'qa';
    }
    if(moduleName === 'execution' && methodLowerCase === 'all')
    {
        return (link.params.from || link.params.$3) == 'project' ? 'project' : 'execution';
    }
    if(moduleName === 'issue' || moduleName === 'risk' || moduleName === 'opportunity' || moduleName === 'pssp' || moduleName === 'auditplan' || moduleName === 'meeting' || moduleName === 'nc')
    {
        if(link.params.$2 == 'my' || link.params.from == 'my') return 'my';
        if(link.params.$2 == 'project' || link.params.from == 'project') return 'project';
        if(link.params.$2 == 'execution' || link.params.from == 'execution') return 'execution';
    }
    if(moduleName === 'product')
    {
        if(methodLowerCase === 'create' && (link.params.programID || link.params.$1)) return 'program';
        if(methodLowerCase === 'edit' && (link.params.programID || link.params.$4)) return 'program';
        if(methodLowerCase === 'batchedit') return 'program';
        var moduleGroup = link.params.moduleGroup ? link.params.moduleGroup : link.params.$2;
        if(methodLowerCase === 'showerrornone' && moduleGroup) return moduleGroup;
    }
    if(moduleName === 'stakeholder')
    {
        if(methodLowerCase === 'create' && (link.params.programID || link.params.$1)) return 'program';
    }
    if(moduleName === 'user')
    {
        if(['todo', 'todocalendar', 'effortcalendar', 'effort', 'task', 'todo', 'story', 'bug', 'testtask', 'testcase', 'execution', 'dynamic', 'profile', 'view', 'issue', 'risk'].includes(methodLowerCase)) return 'system';
    }
    if(moduleName === 'my')
    {
        if(['team'].includes(methodLowerCase)) return 'system';
    }
    if(moduleName === 'company') if(methodLowerCase == 'browse') return 'admin';
    if(moduleName === 'opportunity' || moduleName === 'risk' || moduleName == 'trainplan') if(methodLowerCase == 'view') return 'project';
    if(moduleName === 'tree')
    {
        if(methodLowerCase === 'browse')
        {
            var viewType = link.params.view || link.params.$2;
            if(['bug', 'case', 'caselib'].includes(viewType)) return link.params.$5 === 'project' ? 'project' : 'qa';

            if(viewType === 'doc' && (link.params.from === 'product' || link.params.$5 == 'product')) return 'product';
            if(viewType === 'doc' && (link.params.from === 'project' || link.params.$5 == 'project')) return 'project';
            if(viewType === 'doc')   return 'doc';
            if(viewType === 'story') return 'product';
            if(viewType === 'host')  return 'admin';
            if(viewType === 'ticket') return 'feedback';
        }
        if(methodLowerCase === 'browsetask') return 'execution';
        if(methodLowerCase === 'browsegroup') return 'bi';
    }
    if(moduleName === 'ai' || moduleName === 'zai') return 'aiapp';

    code = navGroup[moduleName] || moduleName || urlOrModuleName;
    return apps.map[code] ? code : defaultCode;
}

/**
 * Search history and go back to specified path.
 *
 * @param {string} target     Back target, can be app name or module-method path.
 * @param {string} url        Fallback url.
 * @param {object} startState Start state.
 * @returns {void}
 */
function goBack(target, url, startState)
{
    const currentState = window.history.state;
    const preState = currentState && currentState.prev;
    if(DEBUG) showLog(null, 'Go back', target, null, {url, startState, currentState, preState});
    if(target && startState && currentState && preState)
    {
        startState = startState.prev || (currentState && currentState.prev);
        if($.apps.openedMap[target])
        {
            let state = startState;
            while(state && state.code !== target) state = state.prev;
            if(state && state.code === target)
            {
                if(state.index === preState.index)
                {
                    if(url) return openApp(url, target, false);
                    return window.history.back();
                }
                return openApp(state.url, state.code, false);
            }
        }
        else
        {
            const pathSet = new Set(target.toLowerCase().split(','));
            let state = startState;
            while(state && state.path && !pathSet.has(state.path.toLowerCase())) state = state.prev;
            if(state && pathSet.has(state.path.toLowerCase()))
            {
                return openApp(state.url, state.code, false);
            }
        }
    }

    if(url) return openApp(url);
    if(target)
    {
        if($.apps.openedMap[target]) return openApp(target);
        if(target.includes('-'))
        {
            const parts = target.split(',').shift().split('-');
            return openApp($.createLink(parts[0], parts[1]));
        }
    }

    window.history.back();
}

/**
 * Logout current user.
 *
 * @param {string} url
 */
function logout(url)
{
    if(!url) url = getLastApp().currentUrl.replace(/^https?:\/\/[^/]+/, '');
    $.get($.createLink('user', 'logout', 'referer=' + btoa(url)), function(data)
    {
        let load = $.createLink('user', 'login');
        try
        {
            data = JSON.parse(data);
            if(data.load != 'login') load = data.load;
        }
        catch (error) {}
        location.href = load;
    });
}

/**
 * Toggle left menu
 * @param {boolean} [toggle]
 * @returns {boolean}
 */
function toggleMenu(toggle)
{
    var $body = $('body');
    if (toggle === undefined) toggle = $body.hasClass('hide-menu');
    $body.toggleClass('hide-menu', !toggle).toggleClass('show-menu', !!toggle);

    $.cookie.set('hideMenu', String(!toggle), {expires: config.cookieLife, path: config.webRoot});
    return toggle;
}

/**
 * Refresh more menu in #menuNav
 * @return {void}
 */
function refreshMenu()
{
    const $mainNav       = $('#menuMainNav');
    const $list          = $('#menuMoreList');
    const $menuNav       = $('#menuNav');
    const $menuItems     = $mainNav.children('li');
    const itemHeight     = $menuItems.first().outerHeight();
    const maxHeight      = $menuNav.outerHeight() - 12;
    const dividerHeight  = 13;
    let showMoreMenu     = false;
    let currentHeight    = itemHeight;
    let moreMenuHeight   = 12;

    $menuItems.each(function()
    {
        var $item     = $(this);
        var isDivider = $item.hasClass('divider');
        var height    = isDivider ? dividerHeight : itemHeight;
        currentHeight += height;

        if(currentHeight > maxHeight)
        {
            $item.addClass('hidden');
            if(!showMoreMenu)
            {
                showMoreMenu = true;
                $list.empty();

                var $prevItem = $item.prev();
                if($prevItem.hasClass('divider')) $prevItem.addClass('hidden');

                if(isDivider) return;
            }
            moreMenuHeight += isDivider ? dividerHeight : itemHeight;
            $list.append($item.clone().toggleClass('menu-item', !isDivider).removeClass('hidden'));
        }
        else
        {
            $item.removeClass('hidden');
        }
    });

    /* The magic number "111" is the space between dropdown trigger btn and the bottom of screen */
    let listStyle = {maxHeight: 'initial', top: moreMenuHeight > 111 ? 111 - moreMenuHeight : ''};
    if($list[0] && $list[0].getBoundingClientRect)
    {
        const btnBounding = $list.prev('a')[0].getBoundingClientRect();
        if(btnBounding.height)
        {
            const winHeight = $(window).height();
            if(winHeight < moreMenuHeight)
            {
                listStyle.maxHeight = winHeight;
                listStyle.overflow = 'auto';
                listStyle.top = 5 - btnBounding.top;
            }
            else if(moreMenuHeight > (winHeight - btnBounding.top))
            {
                listStyle.top = winHeight - btnBounding.top - moreMenuHeight + 5;
            }
        }
    }
    $list.css(listStyle);
    $menuNav.toggleClass('show-more-nav', showMoreMenu);

    if(showMoreMenu && !$list.data('listened-click'))
    {
        $list.data('listened-click', true).on('click', function()
        {
            $list.addClass('hidden');
            setTimeout(function(){$list.removeClass('hidden')}, 200);
        });
    }
}

/** Init apps menu list. */
function initAppsMenu(items)
{
    apps.map.help =
    {
        code:     'help',
        icon:     'icon-help',
        url:      manualUrl || $helpLink.attr('href'),
        external: true,
        text:     manualText || $helpLink.text(),
        appUrl:   config.webRoot + '#app=help'
    };

    const $menuMainNav = $('#menuMainNav').empty();
    (items || appsItems).forEach(function(item)
    {
        const oldItem = apps.map[item.code];
        if(item === 'divider') return $menuMainNav.append('<li class="divider"></li>');
        if(oldItem !== item && oldItem) item = $.extend({}, oldItem, item, {active: oldItem.active});
        item.external = item.external || item.url && item.url.includes('://');

        const $link= $('<a data-pos="menu"></a>')
            .attr('data-app', item.notApp ? undefined : item.code)
            .attr('href', item.url || '#')
            .attr('target', item.notApp ? '_blank' : undefined)
            .addClass('rounded' + (item.notApp ? '' : ' show-in-app'))
            .html(item.title, false);

        item.icon = item.icon || ($link.find('.icon').attr('class') || '').replace('icon ', '');
        item.text = $link.text().trim();
        $link.html('<i class="icon ' + item.icon + '"></i><span class="text">' + item.text + '</span>', false);
        if(['devops', 'bi', 'safe'].includes(item.code)) $link.find('.text').addClass('font-brand');
        apps.map[item.code] = item;

        $('<li class="hint-right"></li>')
            .attr({'data-app': item.code, 'data-hint': item.text})
            .append($link)
            .appendTo($menuMainNav);

        if(!apps.defaultCode) apps.defaultCode = item.code;
    });

    /* 隐藏的App依然可以通过输入URL的形式打开。 */
    allAppsItems.forEach(function(item)
    {
        if(item.code && !apps.map[item.code]) apps.map[item.code] = item;
    });

    const lastApp = getLastApp();
    if(lastApp) $menuMainNav.find('li[data-app="' + lastApp.code + '"]>a').addClass('active');

    apps.map.search =
    {
        opened:     false,
        code:       'search',
        group:      'search',
        icon:       'icon-search',
        methodName: 'index',
        moduleName: 'search',
        text:       langData.search,
        title:      '<i class="icon icon-search"></i> ' + langData.search,
        url:        '/index.php?m=search&f=index',
        vars:       ''
    };

    $('#appTabs').find('.nav-item').each(function()
    {
        const $item = $(this);
        const code = $item.data('app');
        const app = apps.map[code];
        if(!app) return;
        $item.find('.text').text(app.text);
    });
}

/** Update apps menu. */
function updateAppsMenu(includeAppsBar)
{
    loadCurrentPage(
    {
        selector: (includeAppsBar ? '#menuMoreBtn>*,#appsToolbar>*,#visionSwitcher>*,' : '') + 'appsItems()',
        onRender: function(info)
        {
            if(info.name === 'appsItems')
            {
                initAppsMenu(info.data);
                refreshMenu();
                return true;
            }
        }
    });
}

function changeAppsLang(lang)
{
    $('html').attr('lang', lang);
    zui.i18n.setCode(lang);
    $.each(apps.openedMap, function(_code, app)
    {
        if(app.iframe && app.iframe.contentWindow && app.iframe.contentWindow.changeAppLang)
        {
            app.iframe.contentWindow.changeAppLang(lang);
        }
    });
    updateAppsMenu(true);
}

function changeAppsTheme(theme)
{
    changeAppTheme(theme);
    $.each(apps.openedMap, function(_code, app)
    {
        if(app.iframe && app.iframe.contentWindow && app.iframe.contentWindow.changeAppTheme)
        {
            app.iframe.contentWindow.changeAppTheme(theme);
        }
    });
    apps.theme = theme;
    $.get($.createLink('index', 'app'), html =>
    {
        apps.frameContent = html;
        apps.theme = null;
    });
}

function updateUserToolbar()
{
    $.each(apps.openedMap, function(_code, app)
    {
        if(app.iframe && app.iframe.contentWindow && app.iframe.contentWindow.loadPage)
        {
            app.iframe.contentWindow.loadPage({selector: '#toolbar', partial: true, target: '#toolbar'});
        }
    });
}

initAppsMenu();
/* Refresh more menu on window resize */
$(window).on('resize', refreshMenu);
refreshMenu();
setTimeout(refreshMenu, 500);

/* Bind event for menut-toggle */
$(document).on('click', '.menu-toggle', () => toggleMenu());
toggleMenu(!$('body').hasClass('hide-menu'));

/**
 * Get current menu nav data.
 *
 * @returns {Array<{name: string; order: number;}>}
 */
function getMenuNavData()
{
    const data = [];
    const $nav = $('#menuMainNav');
    $nav.children().each(function(index, element) {
        const $elm     = $(element);
        const menuItem = {};
        menuItem.name  = $elm.is('.divider') ? 'divider' : $elm.data('app');
        menuItem.order = index * 5;
        if(typeof $elm.data('hidden') != 'undefined') menuItem.hidden = true;

        data.push(menuItem);
    });

    return data;
}

/**
 * Save menu nav to server.
 */
function saveMenuNavToServer()
{
    const url = $.createLink('custom', 'ajaxSetMenu');
    const data = getMenuNavData();
    $.ajaxSubmit({url, data: {menu: 'nav', items: JSON.stringify(data)}});
}

/**
 * Restore menu nav to server.
 */
function restoreMenuNavToServer()
{
    const url = $.createLink('custom', 'ajaxRestoreMenu');
    $.ajaxSubmit({url, data: {menu: 'nav'}});
    top.location.reload();
}

/**
 * Generate menu nav items to be added.
 *
 * @param {Cash} $item
 * @param {(item: string) => void} onClick click handler of menu item.
 * @returns {Array<{icon: string; text: string; onClick: () => void;}>}
 */
function generateAddMenuNavItems($item, onClick)
{
    const items = canAddDivider($item)
        ? [
            {
                icon: 'icon-minus',
                text: langData.divider,
                onClick: () => {
                    onClick('divider');
                    saveMenuNavToServer();
                }
            }
        ]
        : [];

    const data = getMenuNavData();
    const allAppCodeSet = new Set(allAppsItemsMap.keys());
    for(const {name} of data)
    {
        if(name === 'divider') continue;
        allAppCodeSet.delete(name);
    }

    if(allAppCodeSet.size === 0) return items;
    for(const name of allAppCodeSet)
    {
        const [icon, title] = getAppItemIconAndTitle(name);
        items.push(
            {
                icon,
                text: title,
                onClick: () => {
                    onClick(name);
                    saveMenuNavToServer();
                }
            }
        );
    }
    return items;
}

$(document).on('contextmenu', '#menuMainNav .divider', function(event)
{
    const $divider = $(this);
    const $nav = $divider.closest('.nav');
    const isMoving = $nav.is('[z-use-sortable]');
    const items = [];
    if(isMoving)
    {
        items.push(
            {
                text: langData.save,
                onClick: () => {
                    $divider.closest('.nav').zui().destroy();
                    saveMenuNavToServer();
                }
            }
        );
    }
    else
    {
        items.push(
            {
                text: langData.sort,
                onClick: () => {
                    const sortable = new zui.Sortable(
                        '#menuMainNav',
                        {
                            animation: 150,
                            ghostClass: 'bg-primary-pale',
                            onSort: () => {
                                saveMenuNavToServer();
                            }
                        }
                    );
                }
            }
        );
    }
    items.push(
        {
            text: langData.hide,
            onClick: () => {
                const $li = $divider.closest('li');
                $li.remove();
                refreshMenu();
                saveMenuNavToServer();
            }
        }
    );
    const toAddedItems = generateAddMenuNavItems($divider, addMenuToMainNavCb($divider));
    items.push(
        toAddedItems.length === 0
            ? {
                text: langData.add,
                disabled: true,
            }
            : {
                text: langData.add,
                items: toAddedItems,
            }
    );
    items.push(
        {
            text: langData.restore,
            onClick: () => {
                restoreMenuNavToServer();
            }
        }
    );

    if(apps.openedMenu) apps.openedMenu.hide();
    apps.openedMenu = zui.ContextMenu.show(
        {
            element: $divider[0],
            placement: 'right-start',
            items: items,
            event: event,
            onClickItem: (info) => info.event.preventDefault(),
            onHide: () => apps.openedMenu = null,
        }
    );
    event.preventDefault();
});

/* Bind events for app trigger */
$(document).on('click', '.open-in-app,.show-in-app', function(e)
{
    const $link = $(this);
    if($link.is('[data-modal],[data-toggle],.iframe,.not-in-app')) return;
    const url = $link.attr('href') || $link.data('url');
    if(url && url.includes('onlybody=yes')) return;
    const code = $link.data('app');
    if($.apps.openedMap[code]) showApp(code);
    else openApp(url, $link.data('app'), !$link.hasClass('show-in-app'));
    e.preventDefault();
}).on('contextmenu', '.open-in-app,.show-in-app', function(event)
{
    if(isTutorialMode) return;

    const $btn  = $(this);
    const code = $btn.data('app');
    if(!code) return;

    const app   = apps.openedMap[code];
    const items = [{text: langData.open, disabled: app && getLastAppCode() === code, onClick: () => showApp(code)}];

    if(app) items.push({text: langData.reload, onClick: () => reloadApp(code)});

    if($btn.closest('#menuMainNav').length !== 0)
    {
        const $nav = $btn.closest('.nav');
        const isMoving = $nav.is('[z-use-sortable]');
        if(isMoving)
        {
            items.push(
                {
                    text: langData.save,
                    onClick: () => {
                        $btn.closest('.nav').zui().destroy();
                        saveMenuNavToServer();
                    }
                }
            );
        }
        else
        {
            items.push(
                {
                    text: langData.sort,
                    onClick: () => {
                        const sortable = new zui.Sortable(
                            '#menuMainNav',
                            {
                                animation: 150,
                                ghostClass: 'bg-primary-pale',
                                onSort: () => {
                                    saveMenuNavToServer();
                                }
                            }
                        );
                    }
                }
            );
        }

        const hideDisabled = code === 'my' || $btn.is('.active');
        items.push(
            {
                text: langData.hide,
                onClick: hideDisabled
                    ? null
                    : () => {
                        closeApp(code);
                        const $li = $btn.closest('li');
                        $li.hide().attr('data-hidden', '1');
                        refreshMenu();
                        saveMenuNavToServer();
                        changeApp();
                    },
                disabled: hideDisabled,
            }
        );
        const toAddedItems = generateAddMenuNavItems($btn, addMenuToMainNavCb($btn.closest('li')));
        items.push(
            toAddedItems.length === 0
                ? {
                    text: langData.add,
                    disabled: true,
                }
                : {
                    text: langData.add,
                    items: toAddedItems,
                }
        );

        if(app && code !== 'my') items.push({text: langData.close, onClick: () => closeApp(code)});

        items.push(
            {
                text: langData.restore,
                onClick: () => {
                    restoreMenuNavToServer();
                }
            }
        );
    } else
    {
        if(app && code !== 'my') items.push({text: langData.close, onClick: () => closeApp(code)});
    }

    if(apps.openedMenu) apps.openedMenu.hide();
    apps.openedMenu = zui.ContextMenu.show({element: $btn[0], placement: $btn.closest('#appTabs').length ? 'top-start' : 'right-start', items: items, event: event, onClickItem: function(info){info.event.preventDefault();}, onHide: () => {apps.openedMenu = null}});
    event.preventDefault();
});

$(window).on('popstate', function(event)
{
    const state = event.state;
    if(DEBUG) showLog(state ? state.code : null, 'Popstate', state ? state.url : null, state);
    if(state) openApp(state.url, state.code, false);
});

$.get($.createLink('index', 'app'), html =>
{
    apps.frameContent = html;

    /* Open default app */
    let url = defaultOpen || '';
    let code = '';
    if(location.hash.indexOf('#app=') === 0)
    {
        const params = $.parseUrlParams(location.hash.substring(1));
        code = params.app;
    }
    else if(url.includes(' '))
    {
        const parts = url.split(' ');
        url = parts[0];
        code = parts[1];
    }
    else if(url && $.apps[url])
    {
        code = url;
        url  = '';
    }
    if(!code && defaultOpen)
    {
        const lastOpenApp = zui.store.session.get('lastOpenApp');
        if(lastOpenApp && (lastOpenApp.url === url || lastOpenApp.url.endsWith(url) || $.parseLink(lastOpenApp.url).url === $.parseLink(url).url)) code = lastOpenApp.code;
    }
    if(!url)
    {
        if(!code) url = apps.defaultCode;
        else {url = code; code = '';}
    }
    openApp(url, code);
});

$.apps = $.extend(apps,
{
    openedApps:        apps.openedMap,
    openApp:           openApp,
    open:              openApp,
    reloadApp:         reloadApp,
    showApp:           showApp,
    updateApp:         updateApp,
    updateUrl:         function(appCode, url, title) {return updateApp(appCode, url, title)},
    getLastApp:        getLastApp,
    goBack:            goBack,
    logout:            logout,
    isOldPage:         isOldPage,
    getAppCode:        getAppCode,
    updateAppsMenu:    updateAppsMenu,
    changeAppsLang:    changeAppsLang,
    changeAppsTheme:   changeAppsTheme,
    updateUserToolbar: updateUserToolbar,
    closeApp:          closeApp,
    toggleMenu:        toggleMenu,
    triggerAppEvent:   triggerAppEvent,
    getApps:           getApps,
    getVisibleApps:    getVisibleApps,
    getOpenedApps:     getOpenedApps,
});

window.notifyMessage = function(data)
{
    if(!window.Notification) return;

    var notify  = null;
    var message = data;
    if(typeof data.text == 'string') message = data.text;
    if(Notification.permission == "granted")
    {
        notify = new Notification("", {body:message, tag:'zentao', data:data});
    }
    else if(Notification.permission != "denied")
    {
        Notification.requestPermission().then(function(permission)
        {
            notify = new Notification("", {body:message, tag:'zentao', data:data});
        });
    }

    if(!notify) return;

    notify.onclick = function()
    {
        window.focus();
        if(typeof notify.data.url == 'string' && notify.data.url) window.location.href = notify.data.url;
        notify.close();
    }
    setTimeout(function(){notify.close();}, 3000);
}

window.browserNotify = function()
{
    let windowBlur = false;
    let preCount   = 0;
    setInterval(function()
    {
        if(window.Notification && Notification.permission == 'default') Notification.requestPermission();
        if(window.Notification && Notification.permission == 'granted')
        {
            window.onblur  = function(){windowBlur = true;}
            window.onfocus = function(){windowBlur = false;}
            $('iframe').each(function()
            {
                let iframeID = $(this).attr('id');
                let $this = document.getElementById(iframeID);
                if(document.all)
                {
                    $this.onblur  = function(){windowBlur = true;}
                    $this.onfocus = function(){windowBlur = false;}
                }
                else
                {
                    $this.contentWindow.onblur  = function(){windowBlur = true;}
                    $this.contentWindow.onfocus = function(){windowBlur = false;}
                }
            });
        }

        $.get($.createLink('message', 'ajaxGetMessage', "windowBlur=" + (windowBlur ? '1' : '0')), function(data)
        {
            if(!data) return;
            if(typeof data == 'string') data = JSON.parse(data);
            for(i in data.messages)
            {
                let message = data.messages[i];
                if(windowBlur)
                {
                    if(typeof message.text == 'string') notifyMessage(message);
                }
                else
                {
                    if(!$(message).hasClass('browser-message-content')) continue;
                    zui.Messager.show(
                    {
                        content: {html: message},
                        placement: 'bottom-right',
                        time: 0,
                        icon: 'envelope-o',
                        className: 'bg-secondary-50 text-secondary-600 messager-notice'
                    });
                }
            }

            let unreadCount = parseInt(data.newCount);
            let showCount   = data.showCount != '0';
            let dotStyle    = 'padding: 2px;';
            let rightStyle  = showCount ? 'right: -10px;' : 'right: -2px;';
            if(!showCount) dotStyle += 'width: 5px; height: 5px;';
            if(unreadCount < 10 && showCount) rightStyle = 'right: -5px;';
            if(unreadCount > 99) unreadCount = '99+';

            dotStyle += showCount ? 'top: -3px; aspect-ratio: 0;' : 'top: -2px; aspect-ratio: 1 / 1;';
            dotStyle += rightStyle;

            let dotHtml = '<span class="label danger label-dot absolute' + (showCount ? ' rounded-sm' : '') + '" style="' + dotStyle + '">' + (showCount ? unreadCount : '') + '</span>';
            $('#apps .app-container').each(function()
            {
                let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
                if($iframeMessageBar.length > 0)
                {
                    $iframeMessageBar.find('.label-dot.danger').remove();
                    if(unreadCount) $iframeMessageBar.append(dotHtml);
                }

                let $oldPage = $(this).find('iframe').contents().find('#oldPage');
                if($oldPage.length > 0)
                {
                    $iframeMessageBar = $oldPage.find('iframe').contents().find('#messageBar');
                    if($iframeMessageBar.length  == 0) return;

                    $iframeMessageBar.find('.label-dot.danger').remove();
                    if(unreadCount) $iframeMessageBar.append(dotHtml);
                }
            });
        });
    }, pollTime * 1000);
};

window.clickMessage = function(obj)
{
    let $obj = $(obj);
    let url  = $obj.attr('data-url').replace(/\?onlybody=yes/g, '').replace(/\&onlybody=yes/g, '');
    let messageID = $obj.parent().attr('data-id');
    openApp(url);
    $.get($.createLink('message', 'ajaxMarkRead', 'id=' + messageID));
    $(obj).closest('.alert.messager').find('.alert-close').trigger('click');
}

window.ping = function()
{
    setInterval(function(){$.get($.createLink('misc', 'ping'));}, pollTime * 1000);
}

window.startCron = function(restart)
{
    if(typeof(restart) == 'undefined') restart = 0;
    $.ajax({type:"GET", timeout:100, url:$.createLink('cron', 'ajaxExec', 'restart=' + restart)});
}

turnon ? browserNotify() : ping();
if(runnable) startCron();
if(scoreNotice) zui.Messager.show({ content: {html: scoreNotice}, placement: 'bottom-right', time: 0, icon: 'diamond', className: 'bg-secondary-50 text-secondary-600 score-notice'});

/* Handle clicking outside. */
$(document).on('click', e =>
{
    if($(e.target).attr('id') != 'bizLink' && !$(e.target).parents('#bizLink').length)
    {
        $('#upgradeContent').hide();
        $('#bizLink').removeClass('active');
    }
});

const allAppsItemsMap = new Map();
$(document).ready(
    function()
    {
        for(const item of allAppsItems)
        {
            if(item === 'divider') continue;

            allAppsItemsMap.set(item.code, item);
        }
    }
);

/**
 * Get icon and title of app item.
 *
 * @param {string} name app name
 * @returns {[string, string]}
 */
function getAppItemIconAndTitle(name)
{
    if(!allAppsItemsMap.has(name)) return[];
    const item = allAppsItemsMap.get(name);
    const str = item.title;
    const regex = /class=["']icon (\S*)["']\>\<\/i\>\s(\S*)/;
    const matches = str.match(regex);

    if(matches)
    {
        const icon = matches[1];
        const text = matches[2].trim();
        return [icon, text];
    }
    return [];
}

/**
 * Add menu item to #mainNav callback, used by generateAddMenuNavItems.
 *
 * @param {Cash} $li menu item li
 * @returns {(name: string) => void}
 */
function addMenuToMainNavCb($li) {
    return (name) => {
        if(name === 'divider')
        {
            $li.after('<li class="divider"></li>');
            refreshMenu();
            return
        }

        let item = allAppsItemsMap.get(name);
        const oldItem = apps.map[item.code];
        if(oldItem !== item && oldItem) item = $.extend({}, oldItem, item, {active: false});
        item.external = item.external || item.url && item.url.includes('://');

        const $link= $('<a data-pos="menu"></a>')
            .attr('data-app', item.notApp ? undefined : item.code)
            .attr('href', item.url || '#')
            .attr('target', item.notApp ? '_blank' : undefined)
            .addClass('rounded' + (item.notApp ? '' : ' show-in-app'))
            .html(item.title, false);

        item.icon = item.icon || ($link.find('.icon').attr('class') || '').replace('icon ', '');
        item.text = $link.text().trim();
        $link.html('<i class="icon ' + item.icon + '"></i><span class="text">' + item.text + '</span>', false);
        if(['devops', 'bi', 'safe'].includes(item.code)) $link.find('.text').addClass('font-brand');
        apps.map[item.code] = item;

        $('<li class="hint-right"></li>')
            .attr({'data-app': item.code, 'data-hint': item.text})
            .append($link)
            .insertAfter($li);

        refreshMenu();
        if(!apps.defaultCode) apps.defaultCode = item.code;
    };
}

/**
 * Check whether current element can add a divider.
 * @param {Cash} $item
 * @returns {boolean}
 */
function canAddDivider($item)
{
    $item = $item.closest('li');
    if($item.is('.divider'))        return false;
    if($item.next().is('.divider')) return false;
    if($item.is(':last-child'))     return false;
    return true;
}
