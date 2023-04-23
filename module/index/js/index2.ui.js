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
    lastCode: '',
    zIndex: 10,
    frameContent: null
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
    if(app.iframe && app.iframe.contentWindow.$) return app.iframe.contentWindow.$(app.iframe.contentWindow.document).trigger(event, args);
}

/**
 * Open app
 * @param {string} url
 * @param {string} [code]
 * @param {boolean} [forceReload]
 * @returns {ZentaoOpenedApp|undefined}
 */
function openApp(url, code, forceReload)
{
    if(!code)
    {
        if(apps.map[url])
        {
            code = url;
            url  = '';
        }
        else if(url)
        {
            code = getAppCodeFromUrl(url);
        }
        if(!code) return openApp('my');
    }
    const app = apps.map[code];
    if(!app)
    {
        zui.Messager.show('App not found', {type: 'danger', time: 2000});
        return;
    }
    if(!url) url = app.url;

    /* Create iframe for app */
    let openedApp = apps.openedMap[code];
    if(!openedApp)
    {
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
                'src="' + $.createLink('index', 'app', 'url=' + btoa(url)) + '"',
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

        iframe.onload = iframe.onreadystatechange = function(e)
        {
            const finishLoad = () => $iframe.removeClass('loading').addClass('in');
            iframe.contentWindow.$(iframe.contentDocument).one('pageload.app', finishLoad);
            setTimeout(finishLoad, 10000);
            triggerAppEvent(openedApp.code, 'loadapp', [openedApp, e]);
        };
    }

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
        reloadApp(code, url);
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

    if(debug) console.log('[APPS]', 'open:', code);
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
 */
function reloadApp(code, url)
{
    const app = apps.openedMap[code];
    if(!app) return;

    if(url === true) url = app.url;
    else if(!url) url = app.currentUrl;

    const iframe = app.iframe;
    try
    {
        if(app.external) iframe.src = url;
        else if(iframe.contentWindow.loadPage) iframe.contentWindow.loadPage(url);
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

    const state    = typeof code === 'object' ? code : {code: code, url: url, title: title, type: type};
    const oldState = window.history.state;

    if(title)
    {
        document.title   = title;
        app.currentTitle = title;
    }

    if(oldState && oldState.code === code && oldState.url === url) return;

    const displayUrl = $.createLink('index', 'index2', 'open=' + btoa(url));
    app.currentUrl   = url;
    window.history.pushState(state, title, displayUrl);
    if(debug) console.log('[APPS]', 'update:', {code, url, title, type});
}

/**
 * Get last opened app
 * @param {boolean} [onlyShowed] If set to true then only get last app from apps are showed
 * @returns {object} The opened app info object
 */
function getLastApp(onlyShowed)
{
    let lastShowIndex = 0;
    let lastApp = null;
    Object.values(apps.openedMap).forEach(app =>
    {
        if((!onlyShowed || app.show) && lastShowIndex < app.zIndex && !app.closed)
        {
            lastShowIndex = app.zIndex;
            lastApp = app;
        }
    });
    return lastApp;
}

/**
 * Close app
 * @param {string} code
 * @returns {ZentaoOpenedApp|undefined|false}
 */
function closeApp(code)
{
    code = code || apps.lastCode;
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
    code = code || apps.lastCode;
    const app = apps.openedMap[code];
    if(!app) return;

    $('#menuNav a.active[data-app="' + code + '"]').removeClass('active');

    if(!app.closed) triggerAppEvent(code, 'hideapp', app);

    app.$app.hide();
    apps.lastCode = null;

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
function getAppCodeFromUrl(urlOrModuleName)
{
    var code = navGroup[urlOrModuleName];
    if(code) return code;

    var link = $.parseLink(urlOrModuleName);
    if(!link.moduleName || link.isOnlyBody || (link.moduleName === 'index' && link.methodName === 'index')) return '';

    if(link.hash && link.hash.indexOf('app=') === 0) return link.hash.substr(4);

    /* Handling special situations */
    var moduleName      = link.moduleName;
    var methodName      = link.methodName;
    if (moduleName === 'index' && methodName === 'index') return 'my';

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
        }
        else if(methodLowerCase === 'browsetask')
        {
            return 'project';
        }
    }
    if(moduleName === 'search' && methodLowerCase === 'buildindex') return 'admin';

    code = navGroup[moduleName] || moduleName || urlOrModuleName;
    return apps.map[code] ? code : '';
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
            $list.append($item.clone().removeClass('hidden'));
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

/**
 * Init apps menu list
 */
(() =>
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
    appsItems.forEach(function(item)
    {
        if(item === 'divider') return $menuMainNav.append('<li class="divider"></li>');

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
})();

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
    if(openApp(url, $link.data('app'), !$link.hasClass('show-in-app')))
    {
        e.preventDefault();
    }
}).on('contextmenu', '.open-in-app,.show-in-app', function(event)
{
    const $btn  = $(this);
    const code = $btn.data('app');
    if(!code) return;

    const app   = apps.openedMap[code];
    const items = [{text: lang.open, disabled: app && apps.lastCode === code, onClick: function(){showApp(code)}}];
    if(app)
    {
        items.push({text: lang.reload, onClick: function(){reloadApp(code)}});
        if(code !== 'my') items.push({text: lang.close, onClick: function(){closeApp(code)}});
    }

    const options = {items: items, event: event, onClickItem: function(_item, _$item, e){e.preventDefault();}};
    zui.ContextMenu.show(options);
    event.preventDefault();
});

$(window).on('popstate', function(event)
{
    const state = event.state;
    if(debug) console.log('[APPS]', 'popstate:', state);
    openApp(state.url, state.code, state.type !== 'show');
});

$.get($.createLink('index', 'app'), html =>
{
    apps.frameContent = html;

    /* Open default app */
    let defaultOpenUrl = defaultOpen || apps.defaultCode;
    if(location.hash.indexOf('#app=') === 0)
    {
        const params = $.parseSearchParams(location.hash.substring(1));
        defaultOpenUrl = params.app;
    }
    openApp.apply(null, defaultOpenUrl.split(','));
});

$.apps = $.extend(apps,
{
    openApp:    openApp,
    reloadApp:  reloadApp,
    showApp:    showApp,
    updateApp:  updateApp,
    getLastApp: getLastApp,
});
