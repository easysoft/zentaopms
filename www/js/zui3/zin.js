(function()
{
    if(config.skipRedirect || window.skipRedirect) return;

    const parent        = window.parent;
    const currentModule = config.currentModule;
    const currentMethod = config.currentMethod;
    const isIndexPage   = currentModule === 'index' && currentMethod === 'index';

    const selfOpenList = new Set('index|tutorial|install|upgrade|sso|cron|misc|user-login|user-deny|user-logout|user-reset|user-forgetpassword|user-resetpassword|my-changepassword|my-preference|file-read|file-download|file-uploadimages|report-annualdata|misc-captcha|execution-printkanban|traincourse-playvideo'.split('|'));
    const isAllowSelfOpen = isIndexPage
        || location.hash === '#_single'
        || /(\?|\&)_single/.test(location.search)
        || currentMethod.startsWith('ajax')
        || selfOpenList.has(`${currentModule}-${currentMethod}`)
        || selfOpenList.has(currentModule)
        || $('body').hasClass('allow-self-open');

    if(parent === window && !isAllowSelfOpen)
    {
        const shortUrl = location.pathname + location.search + location.hash;
        location.href = $.createLink('index', 'index', `open=${btoa(shortUrl)}`);
        return;
    }
}());

(function()
{
    let config        = window.config;
    const isIndexPage = config.currentModule === 'index' && config.currentMethod === 'index';

    const DEBUG       = config.debug;
    const currentCode = (window.frameElement ? window.frameElement.name : window.name).split('-')[1];
    const isInAppTab  = parent.window !== window;
    const fetchTasks  = new Map();
    const startTime   = performance.now();
    const timers      = {timeout: [], interval: []};
    let currentAppUrl = isInAppTab ? '' : location.href;
    let zinbar        = null;
    let historyState  = parent.window.history.state;

    $.apps = $.extend(
    {
        currentCode: currentCode,
        updateApp: function(code, url, title)
        {
            const state    = typeof code === 'object' ? code : {url: url, title: title};
            const oldState = window.history.state;

            if(title) document.title = title;

            if(oldState && oldState.url === url) return;

            window.history.pushState(state, title, url);
            if(DEBUG) console.log('[APP]', 'update:', {code, url, title});
            return state;
        },
        updateAppUrl:      function(url, title){return $.apps.updateApp(currentCode, url, title)},
        isOldPage:         () => false,
        reloadApp:         function(_code, url){loadPage(url);},
        openApp:           function(url, options){loadPage(url, options);},
        goBack:            function(){history.go(-1);},
        changeAppsLang:    changeAppLang,
        changeAppsTheme:   changeAppTheme,
        updateUserToolbar: function(){loadPage({selector: '#toolbar', partial: true, target: '#toolbar'})},
    }, parent.window.$.apps);

    const renderMap =
    {
        html:          updatePageWithHtml,
        body:          (data) => $('body').html(data),
        title:         (data) => document.title = data,
        main:          (data) => $('#main').html(data),
        featureBar:    updateFeatureBar,
        pageCSS:       (data) => $('style.zin-page-css').html(data),
        pageJS:        updatePageJS,
        configJS:      updateConfigJS,
        activeMenu:    (data) => activeNav(data),
        navbar:        updateNavbar,
        heading:       updateHeading,
        fatal:         showFatalError,
        hookCode:      updateHookCode,
        zinDebug:      (data, _info, options) => showZinDebugInfo(data, options),
        zinErrors:     (data, _info, options) => showErrors(data, options.id === 'page'),
    };

    function registerRender(name, callback)
    {
        renderMap[name] = callback;
    }

    function showFatalError(data, _info, options)
    {
        const zinDebug = window.zinDebug;
        if(zinDebug && zinDebug.basePath) data = data.split(zinDebug.basePath).join('<i class="icon icon-file-text opacity-50"></i>/');
        if(data.startsWith('<br />\n'))   data = data.replace('<br />\n', '');
        zui.Modal.alert({message: {html: data}, title: `Fatal error: ${options.url}`, actions: [], size: 'lg', custom: {className: 'backdrop-blur border-2 border-canvas bg-opacity-80 rounded-xl', bodyClass: 'font-mono', headerClass: 'text-danger'}});
    }

    function initZinbar()
    {
        if(!DEBUG || DEBUG < 3 || isIndexPage) return;
        let $bar = $('#zinbar');
        if($bar.length) return;

        $bar = $('<div id="zinbar"></div>').insertAfter('body');
        zinbar = new zui.Zinbar($bar[0]);
    }

    function registerTimer(callback, time, type)
    {
        type = type || 'timeout';
        const id = type === 'interval' ? setInterval(callback, time) : setTimeout(callback, time);
        timers[type].push(id);
        return id;
    }

    function updateConfigJS(data)
    {
        $('#configJS').replaceWith(data);
        config = window.config;
        const $body = $(document.body);
        const classList = ($body.attr('class') || '').split(' ').filter(x => x.length && !x.startsWith('m-'));
        classList.push(`m-${config.currentModule}-${config.currentMethod}`);
        $body.attr('class', classList.join(' '));
    }

    function updatePageJS(data)
    {
        if(window.onPageUnmount) window.onPageUnmount();

        window.beforePageLoad = null;
        window.onPageUnmount = null;
        window.beforePageUpdate = null;
        window.afterPageUpdate = null;
        window.onPageRender = null;

        if(timers.interval.length) timers.interval.forEach(clearInterval);
        if(timers.timeout.length) timers.timeout.forEach(clearTimeout);
        timers.interval = [];
        timers.timeout = [];

        zui.Modal.getAll().forEach(m => !m.options.modal && m.hide());
        $('script.zin-page-js').replaceWith(data);
    }

    function updateHookCode(data)
    {
        let $code = $('#zinHookCode');
        if(!$code.length) $code = $('<div id="zinHookCode" class="hidden"></div>').appendTo('body');
        $code.html(data);
    }

    function updateZinbar(perf, errors, basePath)
    {
        if(!DEBUG) return;

        if(zinbar && zinbar.$) zinbar.$.update(perf, errors, basePath);
        else requestAnimationFrame(() => updateZinbar(perf, errors, basePath));
    }

    function updatePerfInfo(options, stage, info)
    {
        if(!DEBUG) return;

        const perf = {id: options.id, url: options.url || currentAppUrl};
        perf[stage] = performance.now();
        if(stage === 'requestBegin') $.extend(perf, {requestEnd: undefined, renderBegin: undefined, renderEnd: undefined});
        if(info)
        {
            if(info.perf) $.extend(perf, info.perf);
            if(info.dataSize) perf.dataSize = info.dataSize;
        }
        updateZinbar(perf);
    }

    function showZinDebugInfo(data, options)
    {
        if(!DEBUG) return;

        if(data.debug)
        {
            data.debug.forEach(dump =>
            {
                console.groupCollapsed('%c ZIN %c debug %c' + dump.name, 'color:#fff;font-weight:bold;background:#ec4899', 'color:#ec4899;font-weight:bold', 'font-weight:bold');
                dump.data.forEach(item =>
                {
                    if(typeof item === 'string') console.log('%c' + item, 'font-family:ui-monospace,monospace; padding:0 4px;border-left:1px solid #ff0000;opacity:0.8');
                    else console.log(item);
                });
                console.groupCollapsed('trace');
                console.log('%c' + dump.trace.join('\n'), 'font-family: ui-monospace,monospace; padding:0 4px;');
                console.groupEnd();
                console.groupEnd();
            });
        }
        updateZinbar({id: options.id, trace: data.trace, xhprof: data.xhprof}, data.errors, data.basePath);
    }

    function updatePageWithHtml(data)
    {
        const html = [];
        const skipTags = new Set(['SCRIPT', 'META']);
        $(data).each(function(_idx, node)
        {
            const nodeName = node.nodeName;
            if(nodeName === '#text') html.push(node.textContent);
            else if(nodeName === 'SCRIPT' && node.innerText.startsWith('window.config={')) html.push(node.outerHTML);
            else if(nodeName === 'TITLE') document.title = node.innerText;
            else if(skipTags.has(nodeName)) return;
            else html.push(node.outerHTML);
        });
        $('body').html(html.join(''));
        window.zin = {config: window.config};
        if(DEBUG) console.log('[ZIN] ', window.zin);
        if(DEBUG) zui.Messager.show({content: 'ZIN: load an old page.', close: false});
    }

    function layoutNavbar(immediate)
    {
        if(!immediate)
        {
            if(layoutNavbar.timer) clearTimeout(layoutNavbar.timer);
            layoutNavbar.timer = setTimeout(() => layoutNavbar(true), 50);
            return;
        }

        const $navbar = $('#navbar');
        if(!$navbar.length) return;

        const $nav = $navbar.children('.nav');
        if(!$nav.length) return;

        let layout        = $nav.data('_layout');
        let itemPadding   = 12;
        let dividerMargin = 8;
        if(!layout)
        {
            layout = {itemCount: 0, contentWidth: 0, dividerCount: 0};
            $nav.find('.nav-item,.nav-divider').each(function()
            {
                const $item = $(this);
                if($item.hasClass('nav-divider'))
                {
                    layout.dividerCount++;
                }
                else
                {
                    layout.itemCount++;
                    layout.contentWidth += $item.width() - (itemPadding * 2);
                }
            });
            $nav.data('_layout', layout);
        }

        const $heading   = $('#heading');
        const totalWidth = $navbar.width();
        const maxWidth   = totalWidth - (2 * itemPadding) - (2 * Math.max($heading.outerWidth() || 0, $('#toolbar').outerWidth() || 0));
        let width = Math.ceil((layout.itemCount * 2 * itemPadding) + layout.contentWidth + layout.dividerCount + (layout.dividerCount * dividerMargin * 2));
        const fixSize = width > maxWidth ? Math.ceil((width - maxWidth) / (2 * (layout.itemCount + layout.dividerCount))) : 0;
        itemPadding -= Math.min(7, fixSize);
        dividerMargin -= Math.min(7, fixSize);
        $nav.css({'--nav-item-padding': itemPadding + 'px', '--nav-divider-margin': dividerMargin + 'px', '--nav-offset-left': 0}).toggleClass('compact', fixSize > 6).toggleClass('compact-extra', fixSize > 8);
        const navLeft      = $nav[0].getBoundingClientRect().left;
        const headingRight = $heading[0].getBoundingClientRect().right;
        $nav.css('--nav-offset-left', Math.max(0, 20 + 2 * Math.ceil(headingRight - navLeft)) + 'px');
    }

    function updateNavbar(data)
    {
        const $navbar = $('#navbar');

        const $newNav = $(data);
        if($newNav.text().trim() !== $navbar.text().trim() || $newNav.find('.nav-item>a').map((_, element) => element.href).get().join(' ') !== $navbar.find('.nav-item>a').map((_, element) => element.href).get().join(' ')) return $navbar.empty().append($newNav);

        activeNav($newNav.find('.nav-item>a.active').data('id'), $navbar);
        layoutNavbar();
    }

    function updateFeatureBar(data)
    {
        const $featureBar = $('#featureBar');
        const isOpenSearch = $featureBar.find('.search-form-toggle.active').length && $featureBar.closest('.show-search-form').length;
        $featureBar.html(data);
        if(isOpenSearch)
        {
            const $searchToggle = $featureBar.find('.search-form-toggle');
            if(!$searchToggle.hasClass('active'))
            {
                zui.toggleSearchForm({module: $searchToggle.data('module'), show: false});
            }
        }
    }

    function updateHeading(data)
    {
        const $data = $(data);
        const $heading = $('#heading');
        const $toolbar = $heading.children('.toolbar');
        if($toolbar.length)
        {
            $heading.children('[data-zui-dropmenu]').each(function()
            {
                const $dropmenu = $(this);
                const $nextDropmenu = $data.filter(`#${$dropmenu.attr('id')}`);
                if(!$nextDropmenu.length) return $dropmenu.remove();
                const options = $nextDropmenu.data();
                const oldOptions = $dropmenu.data();
                if([options.fetcher, options.url, options.text, options.defaultValue].join() === [oldOptions.fetcher, oldOptions.url, oldOptions.text, oldOptions.defaultValue].join()) return;
                const oldDropmenu = $dropmenu.zui('dropmenu');
                if(oldDropmenu)
                {
                    oldDropmenu.render(options);
                    $dropmenu.data(options);
                    const newState = {};
                    if(options.defaultValue !== undefined) newState.value = options.defaultValue;
                    if(options.text !== undefined) newState.text = options.text;
                    oldDropmenu.$.setState(newState);
                }
                else
                {
                    $dropmenu.replaceWith($nextDropmenu);
                }
            });
            $data.filter('[data-fetcher]').each(function()
            {
                const $nextDropmenu = $(this);
                const $dropmenu = $heading.children(`#${$nextDropmenu.attr('id')}`);
                if(!$dropmenu.length) return $heading.append($nextDropmenu);
            });
            const $nextToolbar = $data.filter('.toolbar');
            if($nextToolbar.text().trim() !== $toolbar.text().trim()) $toolbar.replaceWith($nextToolbar);
        }
        else
        {
            $heading.html(data);
        }
        layoutNavbar();
    }

    function activeNav(activeID, nav)
    {
        const $nav    = $(nav || '#navbar');
        const $active = $nav.find('.nav-item>a.active');
        if($active.data('id') === activeID) return;
        $active.removeClass('active');
        $nav.find('.nav-item>a[data-id="' + activeID + '"]').addClass('active');
    }

    function beforeUpdate($target, info, options)
    {
        if($target && $target.length) $target.find('[data-zin-events]').off('.on.zin');
        if(window.beforePageUpdate) window.beforePageUpdate($target, info, options);
    }

    function afterUpdate($target, info, options)
    {
        $target.zuiInit();
        if(window.afterPageUpdate) window.afterPageUpdate($target, info, options);
    }

    function renderPartial(info, options)
    {
        if(window.onPageRender && window.onPageRender(info, options)) return;
        if(options.onRender && options.onRender(info, options)) return;

        const render   = (options.renders ? options.renders[info.name] : null) || renderMap[info.name];
        const isHtml   = info.type === 'html';
        const selector = parseSelector(info.selector);
        let $target    = $(selector.select);
        if(render)
        {
            if(isHtml) beforeUpdate($target, info, options);
            render(info.data, info, options);
            if(isHtml) afterUpdate($target, info, options);
            return;
        }

        /* Common render */
        if(!selector) return console.warn('[APP] ', 'cannot render partial content with data', info);

        if(!$target.length) return console.warn('[APP] ', 'cannot find target element with selector', selector);
        if(selector.first) $target = $target.first();
        if(selector.type === 'json')
        {
            const props = info.data.props;
            if(typeof props !== 'object') return;
            const component = $target.zui(info.name);
            if(typeof component === 'object' && typeof component.render === 'function')
            {
                beforeUpdate($target, info, options);
                component.render(props);
                afterUpdate($target, info, options);
            }
            return;
        }

        beforeUpdate($target, info, options);
        if(selector.inner)
        {
            $target.html(info.data);
        }
        else
        {
            $target.replaceWith(info.data);
            $target = $(selector.select);
        }
        afterUpdate($target, info, options);
    }

    function renderPage(list, options)
    {
        if(DEBUG) console.log('[APP] ', 'render:', list);
        let hasUpdatePage = false;
        list.forEach(item =>
        {
            renderPartial(item, options);
            if(item.name === 'html' || item.name === 'body') hasUpdatePage = true;
        });
        if(hasUpdatePage)
        {
            updatePageLayout();
            $('html').enableScroll();
        }
        if(!options.partial)
        {
            const newState = $.apps.updateApp(currentCode, currentAppUrl, document.title);
            if(newState) historyState = newState;
        }
    }

    function toggleLoading(target, isLoading, loadingClass, loadingIndicatorDelay)
    {
        var $target = $(target);

        loadingClass = loadingClass || 'loading';
        $loginPanel = $target.find('#loginPanel');
        if($loginPanel.length > 0) $target = $loginPanel;

        const position = $target.css('position');
        if(!['relative', 'absolute', 'fixed'].includes(position)) $target.css('position', 'relative');

        if(isLoading === undefined) isLoading = !$target.hasClass(loadingClass);
        $target.css('--load-indicator-delay', isLoading && loadingIndicatorDelay ? loadingIndicatorDelay : null);
        if(!$target.hasClass('load-indicator'))
        {
            $target.addClass('load-indicator');
            if(isLoading) return setTimeout(() => {$target.addClass(loadingClass);}, 50);
        }
        $target.toggleClass(loadingClass, isLoading);
    }

    /**
     * Request data from remote server
     * @param {Object} options
     * @param {string} options.id
     * @param {string} options.url
     * @param {string} options.selector
     * @param {string} [options.target]
     * @param {string} [options.loadingTarget]
     * @param {string} [options.method]
     * @param {FormData|Form|Record<string, unknown>} [options.data]
     * @param {{selector: string, type: string}} [options.zinOptions]
     * @param {function} [options.success]
     * @param {function} [options.error]
     * @param {function} [options.complete]
     * @param {function} [onFinish]
     */
    function requestContent(options, onFinish)
    {
        const target    = options.target || '#main';
        const selectors = (Array.isArray(options.selector) ? options.selector : options.selector.split(',')).map(selector => selector.replace(':component', ':type=json&data=props'));
        const url       = options.url;

        if(DEBUG) console.log('[APP]', 'request', options);
        if(DEBUG && !selectors.includes('zinDebug()')) selectors.push('zinDebug()');
        const isDebugRequest = DEBUG && selectors.length === 1 || selectors[0] === 'zinDebug()';
        if(options.modal === undefined) options.modal = $(target[0] !== '#' && target[0] !== '.' ? `#${target}` : target).closest('.modal').length;
        const headers = {'X-ZIN-Options': JSON.stringify($.extend({selector: selectors, type: 'list'}, options.zinOptions)), 'X-ZIN-App': currentCode, 'X-Zin-Cache-Time': 0};
        if(options.modal) headers['X-Zui-Modal'] = 'true';
        const requestMethod = (options.method || 'GET').toUpperCase();
        if(!options.cache && options.cache !== false) options.cache = requestMethod === 'GET' ? (url + (url.includes('?') ? '&zin=' : '?zin=') + encodeURIComponent(selectors.join(','))) : false;
        const cacheKey = options.cache;
        let cache;
        const renderPageData = (data) =>
        {
            data = data.map((item, idx) =>
            {
                if(Array.isArray(item)) item = {name: item[0].split(':')[0], data: item[1], type: item[2] || 'data'};
                item.selector = selectors[idx];
                return item;
            });
            renderPage(data, options);
        };
        const ajax = new zui.Ajax(
        {
            url:         url + (url.includes('?') ? '&zin=1' : '?zin=1'),
            headers:     headers,
            type:        requestMethod,
            data:        options.data,
            contentType: (options.method || 'GET').toUpperCase(),
            beforeSend: () =>
            {
                updatePerfInfo(options, 'requestBegin');
                if(isDebugRequest) return;
                if(options.loadingTarget !== false) toggleLoading(options.loadingTarget || target, true, options.loadingClass, cache ? '6s' : '1s');
                if(options.before) options.before();
            },
            success(rawData)
            {
                const response = this.response;
                if(response && response.redirected)
                {
                    if(!isInAppTab)
                    {
                        location.href = response.url;
                        return;
                    }
                    if(isInAppTab && currentCode !== $.apps.getAppCode(response.url, currentCode))
                    {
                        $.apps.openApp(response.url, response.url.includes('#app=') ? null : currentCode);
                        return;
                    }
                }
                updatePerfInfo(options, 'requestEnd', {dataSize: rawData.length});
                options.result = 'success';
                let hasFatal = false;
                let data;
                try
                {
                    data = $.parseRawData(rawData);
                }
                catch(e)
                {
                    if(DEBUG) console.error('[APP] ', 'Parse data failed from ' + url, e);
                    if(!isInAppTab && config.zin) return;
                    hasFatal = data.includes('Fatal error') || data.includes('Uncaught TypeError:');
                    data = [{name: hasFatal ? 'fatal' : 'html', data: data}];
                }
                if(Array.isArray(data))
                {
                    updatePerfInfo(options, 'renderBegin');
                    if(!options.partial && !hasFatal) currentAppUrl = (response && response.url) ? (response.url.split('?zin=')[0].split('&zin=')[0]) : url;
                    let newCacheData = cacheKey ? rawData : null;
                    if(newCacheData && DEBUG)
                    {
                        const parts = newCacheData.split(',["zinDebug:<BEGIN>",');
                        if(parts.length > 1) newCacheData = parts[0] + ']';
                    }
                    if(!newCacheData || !cache || newCacheData !== cache.data.data)
                    {
                        renderPageData(data);
                    }
                    else if(DEBUG)
                    {
                        console.log('%c[APP] Skip render data from cache', 'color:green', cacheKey);
                    }
                    updatePerfInfo(options, 'renderEnd');
                    $(document).trigger('pagerender.app');
                    if(options.success) options.success(data);
                    if(onFinish) onFinish(null, data);
                    if(cacheKey)
                    {
                        const cacheTime = +response.headers.get('X-Zin-Cache-Time');
                        $.db.setCacheData(cacheKey, {data: newCacheData, url: currentAppUrl, partial: options.partial, selectors: selectors.join(','), clientTime: Date.now()}, cacheTime, 'zinFetch');
                    }
                }
                else
                {
                    if(data.closeModal) zui.Modal.hide(typeof data.closeModal === 'string' ? data.closeModal : undefined);
                    if(data.autoLoad) autoLoad(data.autoLoad);
                    if(data.result === 'fail')
                    {
                        if(data.message) zui.Messager.show({content: data.message, type: 'danger', className: 'bg-danger text-canvas gap-2 messager-fail'});
                    }
                    if(data.open)
                    {
                        openUrl(data.open);
                    }
                    else if(data.load)
                    {
                        if(data.load === 'table') loadTable();
                        if(data.load === 'login') window.top.location.href = $.createLink('user', 'login', 'referer=' + btoa(url));
                        else if(typeof data.load === 'string') loadPage(data.load);
                        else if(data.load === true) loadCurrentPage();
                        else if(typeof data.load === 'object')
                        {
                            if(data.load.autoLoad) autoLoad(data.load.autoLoad);
                            if('back' in data.load)
                            {
                                openUrl(data.load);
                            }
                            else if('confirm' in data.load)
                            {
                                zui.Modal.confirm({message: data.load.confirm, onResult: function(result)
                                {
                                    if(result && data.load.confirmed) loadPage(data.load.confirmed);
                                    else if(!result && data.load.canceled) loadPage(data.load.canceled);
                                }});
                            }
                            else if('alert' in data.load)
                            {
                                zui.Modal.alert(data.load.alert).then(function()
                                {
                                    if(data.load.modal)  loadModal(data.load.modal);
                                    if(data.load.locate) openUrl(data.load.locate);
                                });
                            }
                            else
                            {
                                loadPage(data.load);
                            }
                        }
                    }
                }
            },
            error: (error, type) =>
            {
                updatePerfInfo(options, 'requestEnd', {error: error});
                if(type === 'abort') return console.log('[ZIN] ', 'Abord fetch data from ' + url, {type, error});;
                if(DEBUG) console.error('[ZIN] ', 'Fetch data failed from ' + url, {type, error});
                zui.Messager.show('ZIN: Fetch data failed from ' + url);
                if(options.error) options.error(data, error);
                if(onFinish) onFinish(error);
            },
            complete: () =>
            {
                if(options.loadingTarget !== false) toggleLoading(options.loadingTarget || target, false, options.loadingClass);
                if(options.complete) options.complete();
                $(document).trigger('pageload.app');
                const frameElement = window.frameElement;
                if(frameElement)
                {
                    frameElement.classList.remove('loading');
                    frameElement.classList.add('in');
                }
            }
        });
        if(currentCode) $.cookie.set('tab', currentCode, {expires: config.cookieLife, path: config.webRoot});
        if(cacheKey)
        {
            $.db.getCache(cacheKey, 'zinFetch').then(localCache =>
            {
                cache = localCache;
                if(cache)
                {
                    ajax.setting.headers['X-Zin-Cache-Time'] = cache.updateTime;
                    try
                    {
                        const data = $.parseRawData(cache.data.data);
                        if(!cache.data.partial) currentAppUrl = cache.data.url;
                        renderPageData(data);
                        $(document).trigger('pagecaheload.app');
                    }
                    catch(error)
                    {
                        if(DEBUG) console.error('[APP] ', 'Parse cache data failed from ' + url, {error: error, cache: cache});
                    }
                }
                ajax.send();
            });
        }
        else
        {
            ajax.send();
        }
        return ajax;
    }

    function fetchContent(url, selectors, options)
    {
        if(typeof url === 'object')
        {
            options = url;
            url = options.url;
            selectors = options.selector;
        }
        if(typeof options === 'string') options = {id: options};
        else if(typeof options === 'function') options = {success: options};

        selectors = Array.isArray(selectors) ? selectors.join(',') : selectors;
        const id = selectors.includes('pageJS') ? 'page' : (options.id || selectors);
        options = $.extend({}, options, {url: url, selectors: selectors, id: id});

        const task = fetchTasks.get(id) || {url: url, selectors: selectors, options: options};
        if(task.xhr)
        {
            if(task.url === url) return;
            task.xhr.abort();
            task.xhr = null;
        }
        fetchTasks.set(id, task);
        if(task.timerID) clearTimeout(task.timerID);
        task.timerID = setTimeout(() =>
        {
            task.timerID = 0;
            task.xhr = requestContent(options, () =>
            {
                const runID = task.xhr.getResponseHeader('Xhprof-RunID');
                updateZinbar({id: id, xhprof: `${config.webRoot}xhprof/xhprof_html/index.php?run=${runID}&source=${config.currentModule}_${config.currentMethod}`});
                task.xhr = null;
                fetchTasks.delete(id);
            });
        }, options.delayTime || 0);
    }

    /** Load an old page. */
    function loadOldPage(url)
    {
        let $page = $('#oldPage');
        if(!$page.length)
        {
            $page = $('<div/>')
                .append($('<iframe />').attr({name: `app-${currentCode}-old`, frameborder: 'no', scrolling: 'auto', style: 'width:100%;height:100%;'}))
                .attr({id: 'oldPage', class: 'canvas fixed w-full h-full top-0 left-0', style: 'z-index:100;'})
                .insertAfter('body')
                .on('oldPageLoad.app', () =>
                {
                    const frame = window.frameElement;
                    frame.classList.remove('loading');
                    frame.classList.add('in');
                    const $iframe = $page.find('iframe').removeClass('invisible');
                    $page.removeClass('loading').find('iframe').addClass('in');
                    $(document).trigger('pageload.app');
                    const iframeWindow = $iframe[0].contentWindow;
                    iframeWindow.$(iframeWindow.document).on('click', () => window.parent.$('body').trigger('click'));
                });
        }
        if($page.hasClass('hidden')) $page.addClass('loading').removeClass('hidden');
        const $iframe = $page.find('iframe').removeClass('in').addClass('invisible');
        if($iframe.attr('src') === url && $iframe[0].contentWindow.location.href === url) $iframe[0].contentWindow.location.reload();
        else $iframe.attr('src', url);
        currentAppUrl = url;
    }

    /** Hide old page content. */
    function hideOldPage()
    {
        const $page = $('#oldPage');
        if(!$page.length) return;
        $page.addClass('in hidden');
    }

    function getLoadSelector(selector)
    {
        if(!selector) return $('#main').length ? '#main>*,pageCSS/.zin-page-css>*,pageJS/.zin-page-js,hookCode(),#configJS,title>*,#heading>*,#navbar>*,#pageToolbar>*' : 'body>*,title>*,#configJS';
        if(selector[0] === '+') return getLoadSelector() + ',' + selector.substring(1);
        return selector;
    }

    /**
     * Load page with zin way.
     *
     * @param {Object}   [options]
     * @param {string}   [options.url]
     * @param {string}   [options.selector]
     * @param {string}   [options.id]
     * @param {string}   [options.method]
     * @param {FormData|Form|Record<string, unknown>} [options.data]
     * @param {string}   [options.target]
     * @param {function} [options.success]
     * @param {function} [options.error]
     * @param {function} [options.complete]
     * @param {string}   [selector]
     * @returns {void}
     */
    function loadPage(options, selector)
    {
        if(typeof options === 'string') options = {url: options};
        options = options || {};

        if(options.url && (options.method || '').toLowerCase() !== 'post' && $.apps.isOldPage(options.url)) return loadOldPage(options.url);
        else hideOldPage();

        if(typeof selector === 'string')      options.selector = selector;
        else if(typeof selector === 'object') options = $.extend({}, options, selector);
        if(!options.selector && options.url && options.url.includes(' '))
        {
            const parts = url.split(' ', 2);
            options.url      = parts[0];
            options.selector = parts[1];
        }

        options  = $.extend({url: currentAppUrl, id: options.selector || options.target || 'page'}, options, {selector: getLoadSelector(options.selector)});

        if(window.beforePageLoad)
        {
            const newOptions = window.beforePageLoad(options);
            if(newOptions) $.extend(options, newOptions);
        }
        if(DEBUG)
        {
            console.groupCollapsed('[APP] ', 'load:', options.url);
            console.trace('options', options);
            console.groupEnd();
        }
        fetchContent(options.url, options.selector, options);
    }

    function loadPartial(url, selector, options)
    {
        loadPage($.extend({partial: true, url: url, selector: selector, target: selector.replace('>*','')}, options));
    }

    /** Load zui component. */
    function loadComponent(target, options)
    {
        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        options = $.extend({url: currentAppUrl, id: target, target: target}, options);
        const $target = $(target);
        if(!$target.length) return loadPage({url: options.url, id: target});

        if($target.closest('.modal').length)
        {
            if(options.modal === undefined)   options.modal = true;
            if(options.partial === undefined) options.partial = true;
        }
        if(!options.selector)
        {
            let name = options.component;
            if(!name)
            {
                const component = $(target).zui();
                if(!component) return;
                name = component.constructor.ZUI;
            }
            options.selector = `${name}/${target}:component`;
        }
        loadPage(options);
    }

    /**
     * Load dtable content.
     *
     * @param {string} [url]
     * @param {string} [target]
     * @param {Object} [options]
     * @returns
     */
    function loadTable(url, target, options)
    {
        if(!url) url = currentAppUrl;
        if(!target)
        {
            const urlInfo = $.parseLink(url);
            target = urlInfo.moduleName ? `table-${urlInfo.moduleName}-${urlInfo.methodName}` : ($('.dtable').attr('id') || 'dtable');
        }
        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        let selector = `dtable/${target}:component`;
        options = options || {};
        if(options.selector && options.selector !== 'dtable') selector = options.selector;
        const isInModal = $(target).closest('.modal').length;
        if(!isInModal && !options.selector)
        {
            selector += ',#mainMenu>*,pageJS/.zin-page-js,hookCode()';
            if($('#moduleMenu').length) selector += ',#moduleMenu,.module-menu-header';
            if($('#docDropmenu').length) selector += ',#docDropmenu,.module-menu';
        }
        delete options.selector;
        return loadComponent(target, $.extend({component: 'dtable', url: url, selector: selector, modal: isInModal}, options));
    }

    /**
     * Load modal content.
     *
     * @param {string} [url]
     * @param {string} [target]
     * @param {Object} [options]
     * @param {Function} [callback]
     * @returns
     */
    function loadModal(url, target, options, callback)
    {
        if(!url)
        {
            const lasShowModal = zui.Modal.query(undefined, undefined, modal => modal.shown);
            if(!lasShowModal) return;
            url = lasShowModal.options.url;
            target = lasShowModal.id;
        }
        options  = $.extend(typeof url === 'object' ? (url || {}) : {url: url}, options);
        target   = target || options.target;
        callback = callback || options.callback;

        if(!target || target === 'current')
        {
            const lastModal = zui.Modal.last();
            if(!lastModal) return zui.Modal.open(options);
            target = lastModal.id;
        }

        if(typeof target === 'string' && target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        const modal = zui.Modal.query(target);
        if(!modal) return;
        if((modal.options.url || '').toLowerCase() === (options.url || '').toLowerCase() && options.loadingClass === undefined) options.loadingClass = '';
        modal.render(options).then((result) => {if(result && callback) callback(modal.dialog);});
    }

    function loadTarget(url, target, options)
    {
        options = $.extend({}, options);
        if(typeof target === 'string') options.target = target;
        else if(typeof target === 'object') options = $.extend(options, target);
        if(typeof url === 'string') options.url = url;
        else if(typeof url === 'object') options = $.extend(options, url);
        if(!options.target) return loadPage(options);

        let remoteData;
        let loadError;
        target = options.target;
        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        const $target = $(target);
        if(!$target.length) return;
        if(options.cache)
        {
            const cache = (typeof options.cache === 'number' ? options.cache : 3600) *  1000;
            const lastLoad = $target.data('zin-target-load');
            if(lastLoad && (Date.now() - lastLoad) < cache) return;
        }

        const ajaxOptions =
        {
            url:         options.url,
            header:      options.header,
            type:        options.method || 'GET',
            data:        options.data,
            contentType: options.contentType,
            beforeSend: () =>
            {
                toggleLoading(options.target, true);
                if(options.beforeSend) return options.beforeSend();
            },
            success: (data) =>
            {
                remoteData = data;
                if(options.beforeUpdate)
                {
                    const result = options.beforeUpdate(data, options);
                    if(result === false) return;
                    if(typeof result === 'string') data = result;
                }

                if(options.success) options.success(data, options);
                let $content = $(data);
                if(options.selector) $content = $('<div>').append($content).find(options.selector);
                if(options.replace) $target.replaceWith($content);
                else $target.empty().append($content);
                $target.data('zin-target-load', Date.now()).zuiInit();
            },
            error: (xhr, type, error) =>
            {
                loadError = error;
                if(options.error) options.error(error, options);
                if(DEBUG) console.error('[ZIN] ', 'Fetch data failed from ' + url, {xhr, type, error});
                zui.Messager.show('ZIN: Fetch data failed from ' + url);
            },
            complete: () =>
            {
                toggleLoading(target, false);
                if(options.complete) options.complete(remoteData, loadError, options);
            }
        };
        return $.ajax(ajaxOptions);
    }

    function postAndLoadPage(url, data, selector, options)
    {
        if(typeof selector === 'object')
        {
            options = selector;
            selector = null;
        }
        options = $.extend({url: url, selector: selector, method: 'POST', data, contentType: false}, options);
        if(options.dataMap)
        {
            options.data = zui.createFormData(zui.mapFormData(options.dataMap), options.data);
            delete options.dataMap;
        }
        if(!options.data || !(options.data instanceof FormData || Object.keys(options.data).length)) return;

        if(options.app) openPage(url, options.app, options);
        else            loadPage(options);
    }

    function loadCurrentPage(options)
    {
        if(options instanceof Event || options instanceof HTMLElement)
        {
            options = {};
            if(DEBUG) console.warn('[APP] ', 'loadCurrentPage() should not be called with an event or element.');
        }
        if(typeof options === 'string') options = {selector: options};
        return loadPage(options);
    }

    function reloadPage()
    {
        loadPage({url: currentAppUrl, selector: 'body>*,title>*,#configJS'});
    }

    /**
     * Load form with zin request.
     *
     * @param {{target: string, url?: string, partial?: boolean, keep?: boolean|string, items?: string, updateActionUrl?: string}} options Load form options.
     */
    function loadForm(options)
    {
        options = $.extend({target: 'form', updateActionUrl: true}, options);
        const $target = $(options.target);
        if(!$target.length) return console.warn('[APP] ', `cannot find target with selector "${options.target}"`);

        let $form = $target.closest('form');
        if(!$form.length) $form = $target.find('form');
        if(!$form.length) return console.warn('[APP] ', `cannot find form from target "${options.target}"`);

        if(options.partial === undefined) options.partial = !!$target.closest('.modal').length;

        const id = $form.attr('id');
        if(!id) return console.warn('[APP] ', `form from "${options.target}" has no id`);

        const formData = new FormData($form[0]);
        const data     = {};
        formData.forEach((value, key) =>
        {
            if(key.includes('[')) key = key.substring(0, key.indexOf('['));
            data[key] = value;
        });

        if(DEBUG)
        {
            console.groupCollapsed('[APP] ', 'loadForm:', id);
            console.log('options', options);
            console.trace('data', data);
            console.groupEnd();
        }

        const $oldItems = $form.children('.form-group[data-name]');
        let items = options.items || [];
        if(typeof items === 'string') items = items.split(',');
        const loadingTarget = items ? $oldItems.filter((_, element) => items.includes($(element).attr('data-name'))) : `#${id}`;
        let url = options.url || $form.attr('data-load-url');
        url = url ? zui.formatString(url, data).replace(/\{\w+\}/g, '') : currentAppUrl;
        loadPage(
        {
            url:           url,
            selector:      `#${id}`,
            partial:       options.partial,
            loadingTarget: loadingTarget,
            loadingClass:  'pointer-events-none',
            success:       () =>
            {
                let updateActionUrl = options.updateActionUrl;
                if(updateActionUrl === true) updateActionUrl = url;
                if(updateActionUrl) $form.attr('action', updateActionUrl);
            },
            onRender:      (info) =>
            {
                if(info.name !== id) return;

                if(options.items)
                {
                    const $data = $(info.data).children('.form-group[data-name]');
                    items.forEach(name =>
                    {
                        const $item = $data.filter(`[data-name="${name}"]`);
                        $oldItems.filter(`[data-name="${name}"]`).replaceWith($item);
                        $item.zuiInit();
                    });

                    if(options.updateOrders)
                    {
                        const formGrid = $form.zui();
                        if(formGrid)
                        {
                            const orders = [];
                            $data.each((_, element) => orders.push($(element).attr('data-name')));
                            formGrid.updateOrders(orders, $form.data('fullModeOrders'));
                        }
                    }
                }
                else
                {
                    $form.html(info.data).zuiInit();
                    const formGrid = $form.zui();
                    if(formGrid) formGrid.toggleMode(formGrid.mode, true);
                }

                let keep = options.keep;
                if(keep === true) keep = Object.keys(data);
                else if(typeof keep === 'string') keep = keep.split(',');

                if(Array.isArray(keep))
                {
                    keep.forEach((name) =>
                    {
                        const $item = $form.find(`[name="${name}"]`);
                        if(!$item.length) return;
                        const value = data[name];
                        if($item.is('.pick-value'))
                        {
                            const pick = $item.closest('.pick').zui();
                            if(pick) pick.$.setValue(value, true);
                        }
                        else if($item.is('select'))
                        {
                            $item.find(`option[value="${value}"]`).prop('selected', true);
                        }
                        else if($item.is(':checkbox'))
                        {
                            $item.prop('checked', value);
                        }
                        else
                        {
                            $item.val(value);
                        }
                    });
                }
                return true;
            }
        })
    }

    function openPage(url, appCode, options)
    {
        if(DEBUG) console.log('[APP] ', 'open:', url, appCode);
        if(!window.config.zin)
        {
            location.href = $.createLink('index', 'app', 'url=' + btoa(url));
            return;
        }
        $.apps.openApp(url, $.extend({code: appCode, forceReload: true}, options));
    }

    function autoLoad(id)
    {
        const parseID = (x) =>
        {
            const parts = x.split(':');
            return {type: parts[0], ids: parts[1] ? parts[1].split(',') : []};
        };
        id = parseID(id);
        const idSet = new Set(id.ids);
        const type = id.type;
        $('[data-auto-load]').each(function()
        {
            const $this = $(this);
            const targetID = parseID($this.attr('data-auto-load'));
            if(type !== targetID.type || (targetID.ids.length && !targetID.ids.some(x => idSet.has(x)))) return;
            const modal = zui.Modal.query($this);
            if(modal) modal.render({loadingClass: ''});
            else loadCurrentPage();
        });
    }

    /**
     * Search history and go back to specified path.
     *
     * @param {string} target Back target, can be app name or module-method path.
     * @param {string} url    Fallback url.
     * @returns {void}
     */
    function goBack(target, url)
    {
        if(!target || target === 'APP' || target === true) target = currentCode;
        else if(target === 'GLOBAL') target = '';
        $.apps.goBack(target, url, historyState);
    }

    /**
     * Open url in app.
     * @param {string} url
     * @param {Object} options
     * @param {string} options.url
     * @param {string} options.app
     * @param {string} options.load
     * @param {string} options.back
     * @param {Event}  event
     */
    function openUrl(url, options, event)
    {
        if(typeof url === 'object')
        {
            options = url;
            url = options.url;
        }
        else
        {
            options = options || {};
            options.url = url;
        }

        if(DEBUG) console.log('[APP] open url', url, options);

        if(options.app === 'current') options.app = currentCode;
        if(options.confirm)
        {
            return zui.Modal.confirm(options.confirm).then(confirmed =>
            {
                if(confirmed) openUrl(url, $.extend({}, options, {confirm: false}), event);
                else if(options.canceled) openUrl(options.canceled, $.extend({}, options, {confirm: false}), event);
            });
        }

        const load = options.load;
        if(typeof load === 'string' || load)
        {
            if(!options.target) options.target = options.loadId;
            if(url) options.url = url;
            if(load)
            {
                if(load === 'post') return postAndLoadPage(options.url, options.data, options);
                if(load === 'table')
                {
                    if(!options.target && event) options.target = $(event.target).closest('.dtable').attr('id');
                    return loadTable(options.url, options.target, options);
                }
                if(load === 'modal')
                {
                    if(!options.target && event) options.target = $(event.target).closest('.modal').attr('id');
                    return loadModal(options);
                }
                if(load === 'target') return loadTarget(options);
                if(load !== 'APP' && typeof load === 'string') options.selector = load;
                delete options.load;
            }
            return loadPage(options);
        }

        if(typeof options.back === 'string') return goBack(options.back, url);

        openPage(url, options.app);
    }

    /**
     * Parse wg selector.
     * @param {string} selector
     * @return {object|null}
     */
    function parseSelector(selector)
    {
        selector = selector.trim();
        let len = selector.length;

        if(len < 1) return null;

        const result = {class: [], id: '', tag: '', inner: false, name: '', first: false, selector: selector};
        if(selector.includes('/'))
        {
            const parts = selector.split('/', 2);
            result.name = parts[0];
            selector    = parts[1];
            len         = selector.length;
        }
        selector = selector.replace('> *', '>*');
        if(selector.endsWith('>*'))
        {
            result.inner = true;
            selector = selector.substring(0, selector.length - 2);
            len      = selector.length;
        }

        let type    = 'tag';
        let current = '';
        let updateResult = function(result, current, type)
        {
            if(!current.length) return;

            if(type === 'class')
            {
                result[type].push(current);
            }
            else if(type === 'option')
            {
                current.split('&').forEach(function(option)
                {
                    const parts = option.split('=');
                    result[parts[0]] = parts[1] || true;
                });
            }
            else
            {
                result[type] = current;
            }
        };

        for(let i = 0; i < len; i++)
        {
            let c = selector[i];
            let t = '';

            if(c === '#' && type !== 'option')
            {
                t = 'id';
            }
            else if(c === '.' && type !== 'option')
            {
                t = 'class';
            }
            else if(c === '(' && type !== 'option' && selector.endsWith(')'))
            {
                let command = selector.substring(i + 1, selector.length - 1);
                if(!command) command = current;
                result.command = command;
                break;
            }
            else if(c === ':')
            {
                t = 'option';
            }

            if(!t)
            {
                current += c;
            }
            else
            {
                updateResult(result, current, type);
                current = '';
                type    = t;
            }
        }
        updateResult(result, current, type);

        if(!result.name.length)
        {
            if(result.id.length) result.name = result.id;
            else if(result.tag)  result.name = result.tag;
            else                 result.name = selector;
        }
        result.select = [result.tag, result.id.length ? '#' + result.id : '', result.class.length ? '.' + result.class.join('.') : ''].join('');

        return result;
    }

    /**
     * Ajax send score to server.
     * @param {string} method
     */
    function ajaxSendScore(method)
    {
        $.get($.createLink('score', 'ajax', 'method=' + method));
    }

    /**
     * Change current app language.
     * @param {string} lang
     */
    function changeAppLang(lang)
    {
        if($('html').attr('lang') === lang) return;
        zui.i18n.setCode(lang);
        reloadPage();
        $('html').attr('lang', lang);
    }

    /**
     * Change current app theme.
     * @param {string} lang
     */
    function changeAppTheme(theme)
    {
        const classList = $('html').attr('class').split(' ').filter(x => x.length && !x.startsWith('theme-'));
        classList.push('theme-' + theme);
        $('html').attr('class', classList.join(' '));
        const $theme = $('#zuiTheme');
        const oldPath = $theme.attr('href').split('/');
        oldPath.pop();
        oldPath.push(theme + '.css');
        $theme.attr('href', oldPath.join('/'));
        const userMenu = $('#userMenu-toggle').zui();
        if(!userMenu) return;
        const themeItem = userMenu.options.menu.items.find(x => x.key === 'theme');
        themeItem.items.forEach(item => {item.active = item['data-value'] === theme;});
        userMenu.render({menu: userMenu.options.menu});
    }

    /**
     * Select UI language.
     * @param {string} lang
     */
    function selectLang(lang)
    {
        $.cookie.set('lang', lang, {expires: config.cookieLife, path: config.webRoot});
        ajaxSendScore('selectLang');
        $.apps.changeAppsLang(lang);
    }

    /**
     * Select UI theme.
     * @param {string} lang
     */
    function selectTheme(theme)
    {
        $.cookie.set('theme', theme, {expires: config.cookieLife, path: config.webRoot});
        $.ajaxSendScore('selectTheme');
        $.apps.changeAppsTheme(theme);
    }

    /**
     * Select vision.
     * @param {string} vision
     */
    function selectVision(vision)
    {
        $.get($.createLink('my', 'ajaxSwitchVision', 'vision=' + vision), function()
        {
            window.top.location.href = $.createLink('index', 'index');
        });
    }

    function waitDom(selector, func, times, interval)
    {
        var _times    = times || 100;   //100次
        var _interval = interval || 50; //50毫秒每次
        var _self     = $(selector);
        var _iIntervalID;
        if(_self.length)
        {
            func && func.call(_self);
            return;
        }

        _iIntervalID = setInterval(function()
        {
            if(!_times) clearInterval(_iIntervalID);
            _times <= 0 || _times--;
            _self = $(selector);
            if(_self.length)
            {
                func && func.call(_self);
                clearInterval(_iIntervalID);
            }
        }, _interval);
    }

    function setImageSize(image, maxWidth, maxHeight)
    {
        var $image = $(image);
        if($image.parent().is('a')) return;

        /* If not set maxWidth, set it auto. */
        if(!maxWidth)
        {
            bodyWidth = $('body').width();
            maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
        }
        if(!maxHeight) maxHeight = top.window.innerHeight;

        setTimeout(function()
        {
            let maxHeightStyle = $image.height() > 0 ? 'max-height:' + maxHeight + 'px' : '';
            if(!document.getElementsByClassName('xxc-embed').length && $image.width() > 0 && $image.width() > maxWidth) $image.attr('width', maxWidth);
            $image.wrap('<a href="' + $image.attr('src') + '" style="display:inline-block;position:relative;overflow:hidden;' + maxHeightStyle + '" target="_blank"></a>');
            if($image.height() > 0 && $image.height() > maxHeight) $image.closest('a').append("<a href='###' class='showMoreImage row items-center justify-center h-7 secondary absolute bottom-0 w-full opacity-70 hover:opacity-100' onclick='showMoreImage(this)'>" + window.config.expand + " <i class='icon-angle-down'></i></a>");
        }, 50);
    }

    function showMoreImage(obj)
    {
        $(obj).parents('a').css('max-height', 'none');
        $(obj).remove();
    }

    function updatePageLayout()
    {
        layoutNavbar();
    }

    function handleGlobalClick(e)
    {
        if(e.defaultPrevented) return;
        if(isInAppTab) window.parent.$('body').trigger('click');

        const $target = $(e.target);
        if($target.closest('.not-open-url').length) return;
        const $link = $target.closest('a,.open-url');
        if(!$link.length || $link.hasClass('ajax-submit') || $link.attr('download') || $link.attr('data-on') || $link.hasClass('show-in-app') || $link.hasClass('not-open-url') || ($link.attr('target') || '')[0] === '_') return;

        const href = $link.attr('href');
        if($link.is('a') && (/^(https?|javascript):/.test(href)) && !$link.data('app')) return;

        if($link.hasClass('disabled') || $link.prop('disabled'))
        {
            e.preventDefault();
            return;
        }

        const options = $link.dataset();
        if(options.toggle && options.toggle !== 'tooltip' && !$link.hasClass('open-url')) return;

        const url    = options.url || href;
        const $modal = $link.closest('.modal');
        if(options.loadId)
        {
            options.target = options.loadId;
            delete options.loadId;
        }

        if(url && ((/^(https?|javascript):/.test(url) && !options.app) || url.startsWith('#'))) return;
        if(!url && $link.is('a') && !options.back && !options.load) return;

        if($modal.length)
        {
            if(!options.load && !url) return;
            if(options.load === 'modal' && !options.target) options.target = $modal.attr('id');
            if(options.load === 'table')
            {
                options.partial = true;
                if(!options.url) options.url = $modal.data('zui.Modal').options.url;
            }
            if(options.closeModal) zui.Modal.hide(typeof options.closeModal === 'string' ? options.closeModal : $modal);
            let app = options.app;
            if(!options.app) options.app = $.apps.getAppCode(url, currentCode);
            if(app === currentCode && options.load !== 'modal')
            {
                options.load === 'modal';
                zui.Modal.query($modal).hide();
            }
        }
        else
        {
            if(options.load === 'modal' && !options.target) delete options.load;
        }

        openUrl(url, options, e);
        e.preventDefault();
    }

    function createSelector(name)
    {
        return name.replace(/([[\]])/g, '\\$1');
    };

    function showValidateMessage(message)
    {
        let $firstControl = null;
        Object.entries(message).forEach(([name, msg]) => {
            if (Array.isArray(msg)) {
                msg = msg.join('');
            }
            const nameSelector = createSelector(name);
            const $element = $('.form');
            let $control = $element.find(`#${nameSelector}`);
            if(!$control.length) $control = $element.find(`[name="${nameSelector}"]`);
            if(!$control.length && !name.includes('[')) $control = $element.find(`[name="${nameSelector}[]"]`);
            if($control.hasClass('pick-value')) $control = $control.closest('.pick');
            $control.addClass('has-error');
            const $group = $control.closest('.form-group,.form-batch-control');
            if(!$group.length) return zui.Messager.show({content: msg, type: 'danger', className: 'bg-danger text-canvas gap-2 messager-success'});
            if($group.length)
            {
                let $tip = $group.find(`#${nameSelector}Tip`);
                if(!$tip.length)
                {
                    $tip = $(`<div class="form-tip ajax-form-tip text-danger pre-line" id="${name}Tip"></div>`).appendTo($group);
                }
                $tip.empty().text(msg);
            }
            if(!$firstControl) $firstControl = $control;
        });
        if($firstControl) $firstControl[0]?.focus();
    }

    $.extend(window, {registerRender: registerRender, fetchContent: fetchContent, loadTable: loadTable, loadPage: loadPage, postAndLoadPage: postAndLoadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector, toggleLoading: toggleLoading, openUrl: openUrl, openPage: openPage, goBack: goBack, registerTimer: registerTimer, loadModal: loadModal, loadTarget: loadTarget, loadComponent: loadComponent, loadPartial: loadPartial, reloadPage: reloadPage, selectLang: selectLang, selectTheme: selectTheme, selectVision: selectVision, changeAppLang, changeAppTheme: changeAppTheme, waitDom: waitDom, setImageSize: setImageSize, showMoreImage: showMoreImage, autoLoad: autoLoad, loadForm: loadForm, showValidateMessage: showValidateMessage});
    $.extend($.apps, {openUrl: openUrl});
    $.extend($, {ajaxSendScore: ajaxSendScore, selectLang: selectLang});

    /* Transfer click event to parent */
    $(document).on('click', handleGlobalClick).on('locate.zt', (_e, data) =>
    {
        if(!data) return;
        if(data === true) return loadCurrentPage();
        if(data === 'reload') return reloadPage();
        if(typeof data === 'string')
        {
            if(data === 'table') return loadTable();
            if(data === 'modal') return loadModal();
            if(data === 'login')
            {
                window.top.location.href = $.createLink('user', 'login', 'referer=' + btoa(currentAppUrl));
                return;
            }
            data = {url: data};
        }
        if(data.autoLoad) autoLoad(data.autoLoad);
        if(data.confirm)
        {
            return zui.Modal.confirm(data.confirm).then(confirmed =>
            {
                if(confirmed) $(document).trigger('locate.zt', data.confirmed);
                else $(document).trigger('locate.zt', data.cancelled);
            });
        }
        if(data.alert)
        {
            return zui.Modal.alert(data.alert).then(function()
            {
                if(data.modal)  loadModal(data.modal);
                if(data.locate) openUrl(data.locate);
            });
        }
        if(data.load) return openUrl(data);
        if(data.app) return openPage(data.url + (data.selector ? (' ' + data.selector) : ''), data.app);
        loadPage(data);
    });

    /* Auto layout UI. */
    $(window).on('resize', updatePageLayout);

    if(!isInAppTab && !isIndexPage)
    {
        $(window).on('popstate', function(event)
        {
            const state = event.state;
            if(DEBUG) console.log('[APP]', 'popstate:', state);
            if(state && state.url) openPage(state.url);
        });
    }

    $(() =>
    {
        if(isIndexPage) return;

        initZinbar();

        if(window.defaultAppUrl) loadPage(window.defaultAppUrl);
        if(isInAppTab)
        {
            const frameElement = window.frameElement;
            if(frameElement && parent.window.$) parent.window.$(frameElement).trigger('ready.app');
        }

        if(DEBUG)
        {
            if(window.zinDebug)
            {
                let requestBegin = startTime;
                if(performance.timing) requestBegin -= ((performance.timing.loadEventStart || Date.now()) - (performance.timing.navigationStart || Date.now()));
                else if(window.zinDebug.trace && window.zinDebug.trace.request) requestBegin -= window.zinDebug.trace.request.timeUsed;
                updatePerfInfo({id: 'page'}, 'renderEnd', {id: 'page', perf: {requestBegin: Math.max(0, requestBegin), requestEnd: startTime, renderBegin: startTime}});
                showZinDebugInfo(window.zinDebug, {id: 'page'});
            }
            if(!isInAppTab && !zui.store.get('Zinbar:hidden') && zui.dom.isVisible($('#navbar'))) loadCurrentPage();
        }
    });
}());
