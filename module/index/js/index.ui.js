/**
 * @typedef {Object} ZentaoApp
 * @property {string} code
 * @property {string} icon
 * @property {string} url
 * @property {string} text
 * @property {string} title
 * @property {boolean} active
 * @property {string} group
 * @property {string} moduleName
 * @property {string} methodName
 * @property {string} vars
 * @property {boolean} [external]
 * @property {boolean} [opened]
 * @typedef {Object} ZentaoOpenedProps
 * @property {true} opened
 * @property {HTMLIFrameElement} iframe
 * @property {number} zIndex
 * @property {string} currentTitle
 * @property {string} currentUrl
 * @property {number} [zIndex]
 * @property {HTMLIframe} iframe
 * @property {jQuery<HTMLDivElement>} $app
 * @property {jQuery<HTMLLIElement>} $bar
 * @typedef {ZentaoApp & ZentaoOpenedProps} ZentaoOpenedApp
 */


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
    oldPages: new Set(oldPages)
};

const debug = config.debug;

function triggerAppEvent(code, event, args)
{
    const app = apps.openedMap[code];
    if(!app) return;

    if(debug) console.log('[APPS]', 'event:', event, code, args);
    event = event + '.apps';
    if(!Array.isArray(args)) args = [args];
    if(app.$app) app.$app.trigger(event, args);
    const iframeWindow = app.iframe && app.iframe.contentWindow;
    if(iframeWindow) return iframeWindow.$(iframeWindow.document).trigger(event, args);
}

function isOldPage(url)
{
    if(typeof url !== 'object') url = $.parseLink(url);
    return apps.oldPages.has(`${url.moduleName}-${url.methodName}`.toLowerCase());
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
            code = getAppCode(url);
        }
        if(!code) return openApp('my');
    }
    const app = apps.map[code];
    if(!app) return zui.Messager.show('App not found', {type: 'danger', time: 2000});

    /* Create iframe for app */
    let openedApp = apps.openedMap[code];
    if(!openedApp)
    {
        if(!url) url = app.url;
        openedApp = $.extend({opened: true, url: url, zIndex: 0, currentUrl: url}, app);
        forceReload = false;
        apps.openedMap[code] = openedApp;

        const $iframe =
        $([
            '<iframe',
                'id="appIframe-' + code + '"',
                'name="app-' + code + '"',
                'class="fade"',
                'allowfullscreen="true"',
                'src="' + $.createLink('index', 'app') + '"',
                'frameborder="no"',
                'allowtransparency="true"',
                'scrolling="auto"',
            '/>'
        ].join(' '));
        const iframe = $iframe[0];
        openedApp.iframe = iframe;
        openedApp.$app = $('<div class="app-container load-indicator" id="app-' + code + '"></div>')
            .append($iframe)
            .appendTo('#apps');

        $iframe.on('ready.app', () =>
        {
            openApp(url, code, options);
        });
        iframe.onload = iframe.onreadystatechange = function(e)
        {
            const finishLoad = () => $iframe.removeClass('loading').addClass('in');
            iframe.contentWindow.$(iframe.contentDocument).one('pageload.app', finishLoad);
            setTimeout(finishLoad, 10000);
            triggerAppEvent(openedApp.code, 'loadapp', [openedApp, e]);
        };
        return;
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
    }

    /* Show and load app */
    const isSameUrl = openedApp.currentUrl === url;
    const needLoad = !isSameUrl || forceReload !== false;
    if(needLoad)
    {
        reloadApp(code, url, options);
        openedApp.$app.toggleClass('open-from-hidden', openedApp.zIndex < apps.zIndex)
    }
    else
    {
        updateApp(code, url, openedApp.currentTitle, 'show');
    }
    openedApp.zIndex = ++apps.zIndex;
    openedApp.$app.show().css('z-index', openedApp.zIndex);

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

    if(debug) console.log('[APPS]', 'open:', code, {url, options, forceReload});
    triggerAppEvent(code, 'openapp', [openedApp, {load: needLoad}]);

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
        else if(iframe.contentWindow.loadPage) iframe.contentWindow.loadPage(url, options);
        else console.error('[APPS]', 'reload: Cannot load page when iframe is not ready.');
    }
    catch(error)
    {
        iframe.src = url;
    }

    app.currentUrl = url;
}

function updateApp(code, url, title, type)
{
    const app = apps.openedMap[code];
    if(!app) return;

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

    if(prevState && prevState.code === code && prevState.url === url) return;

    app.currentUrl = url;
    window.history.pushState(state, title, url);
    zui.store.session.set('lastOpenApp', {code, url});
    if(debug) console.log('[APPS]', 'update:', {code, url, title, type});
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
function getAppCode(urlOrModuleName)
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
    if (moduleMethodLower === 'index-index') return 'my';
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
        else return link.prj ? 'project' : 'report';
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
        if(methodLowerCase === 'showerrornone' && (moduleGroup || moduleGroup)) return moduleGroup;
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
        }
        else if(methodLowerCase === 'browsetask')
        {
            return 'execution';
        }
    }
    if(['search-buildindex', 'ai-adminindex'].includes(moduleMethodLower)) return 'admin';

    code = navGroup[moduleName] || moduleName || urlOrModuleName;
    return apps.map[code] ? code : '';
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
    if(debug) console.log('[APPS] go back', {target, url, startState, currentState, preState});
    if(target && currentState && preState)
    {
        startState = startState.prev || (currentState && currentState.prev);
        if($.apps.openedMap[target])
        {
            let state = startState;
            while(state && state.code !== target) state = state.prev;
            if(state && state.code === target)
            {
                if(state.index === preState.index) return window.history.back();
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
                if(state.index === preState.index) return window.history.back();
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
            const parts = target.split('-');
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
    if(!url) url = getLastApp().currentUrl;
    $.get($.createLink('user', 'logout', 'referer=' + btoa(url)), function(data)
    {
        let load = $.createLink('user', 'login');
        try
        {
            data = JSON.parse(data);
            if(data.load) load = data.load;
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

    const $toggle = $('#menuToggleMenu .menu-toggle');
    $toggle.attr('data-title', $toggle.data(toggle ? 'collapseText' : 'unfoldText'));

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
    const $helpLink = $('#helpLink');
    if($helpLink.length)
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
    }

    const $menuMainNav = $('#menuMainNav').empty();
    (items || appsItems).forEach(function(item)
    {
        const oldItem = apps.map[item.code];
        if(item === 'divider') return $menuMainNav.append('<li class="divider"></li>');
        if(oldItem !== item && oldItem) item = $.extend({}, apps.map[item.code], item, {active: oldItem.active});

        const $link= $('<a data-pos="menu"></a>')
            .attr('data-app', item.code)
            .addClass('rounded show-in-app')
            .html(item.title);

        item.icon = ($link.find('.icon').attr('class') || '').replace('icon ', '');
        item.text = $link.text().trim();
        $link.html('<i class="icon ' + item.icon + '"></i><span class="text">' + item.text + '</span>');
        if(item.code === 'devops') $link.find('.text').addClass('num');
        apps.map[item.code] = item;

        $('<li></li>').attr('data-app', item.code)
            .attr({'data-toggle': 'tooltip', 'data-placement': 'right', 'data-title': item.text})
            .append($link)
            .appendTo($menuMainNav);

        if(!apps.defaultCode) apps.defaultCode = item.code;
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
        text:       lang.search,
        title:      '<i class="icon icon-search"></i> ' + lang.search,
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
function updateAppsMenu(includeAppsToolbar)
{
    loadCurrentPage(
    {
        selector: (includeAppsToolbar ? '#appsToolbar>*,' : '') + 'appsItems()',
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

/* Bind events for app trigger */
$(document).on('click', '.open-in-app,.show-in-app', function(e)
{
    const $link = $(this);
    if($link.is('[data-modal],[data-toggle],.iframe,.not-in-app')) return;
    const url = $link.attr('href') || $link.data('url');
    if(url && url.includes('onlybody=yes')) return;
    if(openApp(url, $link.data('app'), !$link.hasClass('show-in-app'))) e.preventDefault();
}).on('contextmenu', '.open-in-app,.show-in-app', function(event)
{
    const $btn  = $(this);
    const code = $btn.data('app');
    if(!code) return;

    const app   = apps.openedMap[code];
    const items = [{text: lang.open, disabled: app && getLastAppCode() === code, onClick: function(){showApp(code)}}];
    if(app)
    {
        items.push({text: lang.reload, onClick: function(){reloadApp(code)}});
        if(code !== 'my') items.push({text: lang.close, onClick: function(){closeApp(code)}});
    }

    zui.ContextMenu.show({hideOthersOnShow: true, key: 'openedAppMenu', element: $btn[0], placement: $btn.closest('#appTabs').length ? 'top-start' : 'right-start', items: items, event: event, onClickItem: function(info){info.event.preventDefault();}});
    event.preventDefault();
});

$(window).on('popstate', function(event)
{
    const state = event.state;
    if(debug) console.log('[APPS]', 'popstate:', state);
    if(state) openApp(state.url, state.code, false);
});

$.get($.createLink('index', 'app'), html =>
{
    apps.frameContent = html;

    /* Open default app */
    let defaultOpenUrl = defaultOpen || apps.defaultCode;
    if(location.hash.indexOf('#app=') === 0)
    {
        const params = $.parseUrlParams(location.hash.substring(1));
        defaultOpenUrl = params.app;
    }
    const parts = defaultOpenUrl.split(' ');
    const url = parts[0];
    let code = parts[1];
    if(!code && defaultOpen)
    {
        const lastOpenApp = zui.store.session.get('lastOpenApp');
        if(lastOpenApp && lastOpenApp.url === url) code = lastOpenApp.code;
    }
    openApp(url, code);
});

$.apps = $.extend(apps,
{
    openApp:        openApp,
    reloadApp:      reloadApp,
    showApp:        showApp,
    updateApp:      updateApp,
    getLastApp:     getLastApp,
    goBack:         goBack,
    logout:         logout,
    isOldPage:      isOldPage,
    getAppCode:     getAppCode,
    updateAppsMenu: updateAppsMenu,
    changeAppsLang: changeAppsLang,
    changeAppsTheme: changeAppsTheme,
    updateUserToolbar: updateUserToolbar
});

window.notifyMessage = function(data)
{
    if(!window.Notification) return;

    var notify  = null;
    var message = data;
    if(typeof data.message == 'string') message = data.message;
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

    setInterval(function()
    {
        if(window.Notification && Notification.permission == 'granted')
        {
            window.onblur  = function(){windowBlur = true;}
            window.onfocus = function(){windowBlur = false;}
        }

        $.get($.createLink('message', 'ajaxGetMessage', "windowBlur=" + (windowBlur ? '1' : '0')), function(data)
        {
            if(!data) return;
            if(!windowBlur)
            {
                if(!$(data).hasClass('browser-message-content')) return false;

                zui.Messager.show(
                {
                    content: {html: data},
                    placement: 'bottom-right',
                    time: 0,
                    icon: 'envelope-o',
                    className: 'primary-pale'
                });
            }
            else
            {
                if(typeof data == 'string') data = JSON.parse(data);
                if(typeof data.message == 'string') notifyMessage(data);
            }
        });
    }, pollTime * 1000);
};

window.ping = function()
{
    setInterval(function(){$.get($.createLink('misc', 'ping'));}, pollTime * 1000);
}

window.startCron = function(restart)
{
    if(typeof(restart) == 'undefined') restart = 0;
    $.ajax({type:"GET", timeout:100, url:$.createLink('cron', 'ajaxExec', 'restart=' + restart)});
}

//$(function()
//{
//    if(showFeatures && vision == 'rnd') loadModal($.createLink('misc', 'features'));
//})

turnon ? browserNotify() : ping();
if(runnable) startCron();
if(scoreNotice) zui.Messager.show({ content: {html: scoreNotice}, placement: 'bottom-right', time: 0, icon: 'diamond', className: 'primary-pale' });
