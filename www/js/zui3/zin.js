(function()
{
    if(config.skipRedirect || window.skipRedirect) return;

    const parent        = window.parent;
    const currentModule = config.currentModule;
    const currentMethod = config.currentMethod;
    const isIndexPage   = currentModule === 'index' && currentMethod === 'index';
    const moduleMethod  = `${currentModule}-${currentMethod}`;

    const selfOpenList = new Set('index|tutorial|install|upgrade|sso|cron|misc|user-login|user-deny|user-logout|user-reset|user-forgetpassword|user-resetpassword|my-changepassword|my-preference|file-read|file-download|file-preview|file-uploadimages|file-ajaxwopifiles|report-annualdata|misc-captcha|execution-printkanban|traincourse-ajaxuploadlargefile|traincourse-playvideo|screen-view|zanode-create|screen-ajaxgetchart|instance-terminal|ai-chat|instance-logs'.split('|'));
    const iframeList = new Set(['cron-index', 'zanode-create']);
    const isAllowSelfOpen = !iframeList.has(moduleMethod) &&
        (isIndexPage
        || location.hash === '#_single'
        || /(\?|\&)_single/.test(location.search)
        || currentMethod.startsWith('ajax')
        || selfOpenList.has(moduleMethod)
        || selfOpenList.has(currentModule)
        || $('body').hasClass('allow-self-open'));

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
    const ridSet      = new Set();
    const timers      = {timeout: [], interval: []};
    let currentAppUrl = isInAppTab ? '' : location.href;
    let zinbar        = null;
    let historyState  = parent.window.history.state;
    const hasZinBar   = DEBUG && window.zin && window.zin.zinTool && !isIndexPage;
    const localCacheFirst = config.clientCache === 'local-first';
    const isTutorial  = top.config.currentModule === 'tutorial';
    let openedOldPage = false;
    let oldPageCofnig = null;
    const zinCallbacks = {
        onSelectLang: null,
        onSelectTheme: null,
        onSelectVision: null,
        onChangeApp: null
    };

    /**
     * 注册Zin回调函数。
     * Register a Zin callback function.
     *
     * @param {string} name - 回调函数名称。
     * @param {function} callback - 回调函数。
     */
    function registerZinCallback(name, callback)
    {
        if(typeof callback === 'function')
        {
            zinCallbacks[name] = callback;
        }
        else
        {
            console.warn('[ZIN] registerZinCallback expects a function for', name);
        }
    }

    function getPageInfo()
    {
        let pageConfig = openedOldPage ? oldPageCofnig : window.config;
        if(openedOldPage && !pageConfig)
        {
            const oldPageLink = $.parseLink(openedOldPage);
            pageConfig = {currentModule: oldPageLink.moduleName, currentMethod: oldPageLink.methodName};
        }
        return {
            app          : currentCode,
            id           : `${currentCode}.${pageConfig.currentModule}-${pageConfig.currentMethod}`,
            path         : `${pageConfig.currentModule}-${pageConfig.currentMethod}`,
            url          : currentAppUrl,
            config       : pageConfig,
            currentModule: pageConfig.currentModule,
            currentMethod: pageConfig.currentMethod,
        };
    }

    function getUrlID(url)
    {
        const info = $.parseLink(url || currentAppUrl);
        return info.moduleName ? `${info.moduleName}-${info.methodName}` : '';
    }

    function isDiffPage(newUrl, oldUrl)
    {
        return getUrlID(newUrl) !== getUrlID(oldUrl || currentAppUrl);
    }

    function showLog(name, moreTitles, trace, moreInfos)
    {
        const titles = [`%c APP • ${currentCode} `];
        const titleColors = ['color:#fff;font-weight:bold;background:#2196f3'];
        const urlID = getUrlID();
        if(urlID)
        {
            titles.push(`%c ${urlID} `);
            titleColors.push('background:rgba(33, 150, 243, 0.2);color:#2196f3;');
        }
        if(name)
        {
            titles.push(`%c ${name} `);
            titleColors.push('color:#2196f3;font-weight:bold;');
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

    $.apps = $.extend(
    {
        currentCode: currentCode,
        updateApp: function(code, url, title)
        {
            const state    = typeof code === 'object' ? code : {url: url, title: title};
            const oldState = window.history.state;

            if(title) document.title = title;

            if(oldState && oldState.url === url)
            {
                if(DEBUG) showLog('Update skipped', [code, title], {oldState, state, code, url, title});
                return;
            }

            window.history.pushState(state, title, url);
            if(DEBUG) showLog('Update', [code, title], {state, code, url, title});
            return state;
        },
        updateAppUrl: function(url, title)
        {
            currentAppUrl = url;
            return $.apps.updateApp(currentCode, url, title);
        },
        isOldPage:         () => false,
        reloadApp:         function(_code, url){loadPage(url);},
        openApp:           function(url, options){loadPage(url, options);},
        goBack:            function(){history.go(-1);},
        changeAppsLang:    changeAppLang,
        changeAppsTheme:   changeAppTheme,
        updateUserToolbar: function(){loadPage({selector: '#toolbar', partial: true, target: '#toolbar'})},
        triggerEvent:      triggerEvent,
    }, parent.window.$.apps);

    const renderMap =
    {
        html:          updatePageWithHtml,
        body:          (data) => $('body').html(data),
        title:         (data) => document.title = data,
        featureBar:    updateFeatureBar,
        pageCSS:       updatePageCSS,
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
        const isNormalPage = data.startsWith('<!DOCTYPE html');
        zui.Modal.showError({error: isNormalPage ? data : `<b>URL</b>: ${options.url}<br>${data}`, size: 'lg', html: !isNormalPage})
    }

    function initZinbar()
    {
        if(!hasZinBar) return;
        let $bar = $('#zinbar');
        if($bar.length) return;

        $bar = $('<div id="zinbar"></div>').insertAfter('body');
        zinbar = new zui.Zinbar($bar[0], typeof window.zin.zinTool === 'object' ? window.zin.zinTool : {});
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
        const pageID = `${config.currentModule}-${config.currentMethod}`;
        classList.push(`m-${pageID}`);
        $body.attr('class', classList.join(' ')).attr('data-page', pageID).attr('data-page-raw', `${config.rawModule}-${config.rawMethod}`);
    }

    function updatePageCSS(data, _info, options)
    {
        let $style = $(`style.zin-page-css[data-id="${options.pageID}"]`);
        if(!$style.length) $style = $('body>style.zin-page-css');
        $style.html(data, false);
    }

    function updatePageJS(data, _info, options)
    {
        if(window.onPageUnmount) window.onPageUnmount();
        $(document).trigger('pageunmount.app');

        ['beforePageLoad', 'beforeRequestContent', 'onPageUnmount', 'beforePageUpdate', 'afterPageUpdate', 'onPageRender', 'afterPageRender', 'getPageFormHelper'].forEach(key =>
        {
            if(window[key]) delete window[key];
        });

        if(timers.interval.length) timers.interval.forEach(clearInterval);
        if(timers.timeout.length)  timers.timeout.forEach(clearTimeout);
        timers.interval = [];
        timers.timeout = [];

        if(!options.modal) zui.Modal.getAll().forEach(m => !m.options.modal && m.hide());
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
        if(!hasZinBar) return;

        if(zinbar && zinbar.$) zinbar.$.update(perf, errors, basePath);
        else requestAnimationFrame(() => updateZinbar(perf, errors, basePath));
    }

    function getPerfData()
    {
        return (zinbar && zinbar.$) ? zinbar.$.state.pagePerf : null;
    }

    function triggerEvent(event, args, options)
    {
        if(!isInAppTab ||!$.apps.triggerAppEvent) return;
        $.apps.triggerAppEvent(currentCode, event, [getPageInfo(), args], options);
    }

    function triggerPerfEvent(stage)
    {
        if(!zinbar || !zinbar.$) return;
        if(zinbar.lastPerfEventType === stage) clearTimeout(zinbar.lastPerfEventTimer);
        zinbar.lastPerfEventTimer = setTimeout(() => {
            triggerEvent('updatePerfData.app', {stage: stage, perf: getPerfData()}, {silent: true});
        }, 100);
        zinbar.lastPerfEventType = stage;
    }

    function updatePerfInfo(options, stage, info)
    {
        if(!hasZinBar) return;

        const perf = {id: options.id, url: options.url || currentAppUrl};
        perf[stage] = options.time || performance.now();
        if(stage === 'requestBegin') $.extend(perf, {requestEnd: undefined});
        else if(stage === 'renderBegin') zinbar.waitZUI = {time: perf[stage], id: options.id, url: options.url};
        if(info)
        {
            if(info.perf) $.extend(perf, info.perf);
            if(info.dataSize) perf.dataSize = info.dataSize;
        }
        updateZinbar(perf);
        triggerPerfEvent(stage);
    }

    function showZinDebugInfo(data, options)
    {
        if(!DEBUG) return;

        if(data.debug)
        {
            window.zinDebug = data.debug;
            data.debug.forEach(dump =>
            {
                console.groupCollapsed(`%c ${currentCode.toUpperCase()} %c ${getUrlID()} %c DEBUG %c${dump.name}`, 'color:#fff;font-weight:bold;background:#ec4899', 'background:rgba(233,30,99,0.2);color:#ec4899;font-weight:bold', 'color:#ec4899;font-weight:bold', 'font-weight:bold');
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
        console.warn('ZIN: load an old page.');
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
        if(
            $newNav.find('.item').length !== $navbar.find('.item').length
            || $newNav.find('.item[data-hidden]').length !== $navbar.find('.item[data-hidden]').length
            || $newNav.text().trim() !== $navbar.text().trim()
            || $newNav.find('.nav-item>a').map((_, element) => element.href).get().join(' ') !== $navbar.find('.nav-item>a').map((_, element) => element.href).get().join(' ')
        ) return $navbar.empty().append($newNav);

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

    function updateHeading(data, info)
    {
        const $heading = $('#heading');
        if(!$heading.length) return;
        const selector = parseSelector(info.selector);
        renderWithHtml($heading, data, selector);
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
        if($target && $target.length) $target.find('[data-zin-events]').off('.zin.on');
        if(window.beforePageUpdate) window.beforePageUpdate($target, info, options);
    }

    function afterUpdate($target, info, options)
    {
        if(window.afterPageUpdate) window.afterPageUpdate($target, info, options);
        triggerEvent('updatepart.app', {target: $target, info, options}, {silent: true});
    }

    function renderWithHtml($target, html, selector, noMorph)
    {
        if(typeof selector === 'string') selector = parseSelector(selector);
        if(config.morphUpdate === false || $target.hasClass('no-morph')) noMorph = true;
        if(selector.inner)
        {
            if(noMorph)
            {
                $target.html(html);
            }
            else
            {
                const tagName = $target.prop('tagName').toLowerCase();
                $target.morphInner(`<${tagName}>${html}</${tagName}>`);
            }
        }
        else
        {
            if(noMorph)
            {
                $target.replaceWith(html);
                $target = $(selector.select);
            }
            else
            {
                $target.morph(html);
            }
        }
        return $target;
    }

    function renderPartial(info, options)
    {
        if(window.onPageRender && window.onPageRender(info, options)) return;
        if(options.onRender && options.onRender(info, options)) return;

        const render   = (options.renders ? options.renders[info.name] : null) || renderMap[info.name];
        const isHtml   = info.type === 'html';
        const selector = parseSelector(info.selector);
        let $target    = selector ? $(selector.select) : null;
        if(render)
        {
            if(isHtml) beforeUpdate($target, info, options);
            render(info.data, info, options);
            if(isHtml) afterUpdate($target, info, options);
            return;
        }

        /* Common render */
        if(!selector)
        {
            if(DEBUG) showLog('Render partial', 'error:cannot render partial content without selector.', {info, options});
            else console.warn('[APP] ', 'cannot render partial content with data', info);
            return;
        }

        if(!$target.length)
        {
            if(DEBUG) showLog('Render partial', 'cannot find target element with selector.', {info, selector, options});
            else console.warn('[APP] ', 'cannot find target element with selector.', info);
            return;
        }
        if(selector.first) $target = $target.first();
        if(selector.type === 'json')
        {
            const props = info.data.props;
            if(typeof props !== 'object') return;
            const component = $target.zui(info.name);
            if(typeof component === 'object' && typeof component.render === 'function')
            {
                $target.closest('[zui-create-dtable]').attr(`zui-create-${component.constructor.ZUI || info.name}`, '');
                beforeUpdate($target, info, options);
                component.render(props);
                afterUpdate($target, info, options);
            }
            return;
        }

        beforeUpdate($target, info, options);
        $target = renderWithHtml($target, info.data, selector, options.noMorph);
        afterUpdate($target, info, options);
    }

    function renderPage(list, options)
    {
        if(DEBUG) showLog('Render', [options.id, list.length, options.noMorph ? 'html' : 'morph'], list, {options});
        let updateFullPage = false;
        list.forEach(item =>
        {
            try{renderPartial(item, options);}
            catch(error) {console.error('[ZIN] ', 'Render partial failed', error, {item, options});}
            if(item.name === 'html' || item.name === 'body') updateFullPage = true;
        });
        if(updateFullPage)
        {
            updatePageLayout();
            $('html').enableScroll();
        }
        if(window.afterPageRender) window.afterPageRender(list, options);
        triggerEvent('updatepage.app', {updateFullPage: updateFullPage, options});
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

        const position = $target.css('position');
        if(!['relative', 'absolute', 'fixed'].includes(position)) $target.css('position', 'relative');

        if(isLoading === undefined) isLoading = !$target.hasClass(loadingClass);
        $target.css('--load-indicator-delay', isLoading && loadingIndicatorDelay ? loadingIndicatorDelay : null);
        if(!$target.hasClass('load-indicator')) $target.addClass('load-indicator');
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
        if(DEBUG && !selectors.includes('zinDebug()')) selectors.push('zinDebug()');
        const isDebugRequest = DEBUG && selectors.length === 1 || selectors[0] === 'zinDebug()';
        if(options.modal === undefined) options.modal = $(target[0] !== '#' && target[0] !== '.' ? `#${target}` : target).closest('.modal').length;
        const headers =
        {
            'X-ZIN-Options': JSON.stringify($.extend({selector: selectors, type: 'list'}, options.zinOptions)),
            'X-ZIN-App': currentCode,
            'X-Zin-Cache-Time': 0,
            'X-ZIN-UID': zui.uid ? zui.uid() : ''
        };
        if(options.modal) headers['X-Zui-Modal'] = 'true';
        if(isTutorial && top.getCurrentStepID) headers['X-ZIN-Tutorial'] = top.getCurrentStepID();
        const requestMethod = (options.method || 'GET').toUpperCase();
        if(!options.cache && options.cache !== false) options.cache = !!(requestMethod === 'GET' && config.clientCache && !options.partial);
        if(options.cache === true) options.cache = url + (url.includes('?') ? '&zin=' : '?zin=') + encodeURIComponent(selectors.join(','));
        const cacheKey = options.cache;
        const rid = options.rid;
        let cache;
        let cacheHit;
        const renderPageData = (data, onlyZinDebug) =>
        {
            const renderOptions = $.extend({noMorph: !config.morphUpdate || (!options.partial && options.isDiffPage)}, options);
            if(!onlyZinDebug) updatePerfInfo(options, 'renderBegin');
            renderPage(data.reduce((list, item, idx) =>
            {
                if(Array.isArray(item)) item = {name: item[0].split(':')[0], data: item[1], type: item[2] || 'data'};
                item.selector = selectors[idx];
                if(!onlyZinDebug || item.name === 'zinDebug') list.push(item);
                return list;
            }, []), renderOptions);
            if(!onlyZinDebug) updatePerfInfo(options, 'renderEnd', {perf: {clientCache: cacheHit ? cacheKey : null}});
        };
        const callCallback = (name, args) =>
        {
            let callback = options[name];
            if(!callback) return;
            if(typeof callback === 'string') callback = zui.evalValue(callback);
            return callback.apply(null, args);
        };
        const ajax = new zui.Ajax(
        {
            url:     url + (url.includes('?') ? '&zin=1' : '?zin=1'),
            headers: headers,
            type:    requestMethod,
            data:    options.data,
            type:    (options.method || 'GET').toUpperCase(),
            beforeSend: () =>
            {
                updatePerfInfo(options, 'requestBegin');
                if(isDebugRequest) return;
                if(options.loadingTarget !== false) toggleLoading(options.loadingTarget || target, true, options.loadingClass, options.loadingIndicatorDelay || (options.partial ? '.2s' : (cache ? '5s' : '1s')));
                if(options.before) options.before();
                if(!options.partial) $('body').addClass('loading-page');
            },
            success(rawData)
            {
                if(!ridSet.has(rid))
                {
                    if(DEBUG) showLog('Request', `error:canceled request ${rid} on success`, {url, rid, options});
                    return;
                }
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
                updatePerfInfo(options, 'requestEnd', {dataSize: rawData.length, perf: {clientCache: cache ? cacheKey : null}});
                options.result = 'success';
                let hasFatal = false;
                let data;

                if(!rawData && !ajax.sendedAgain)
                {
                    ajax.sendedAgain = true;
                    ajax.setting.headers['X-Zin-Debug'] = true;
                    delete ajax.data;
                    delete ajax.error;
                    ajax.send();
                    ajax.canceled = true;
                    return;
                }
                ajax.canceled = false;

                try
                {
                    data = $.parseRawData(rawData);
                }
                catch(e)
                {
                    if(DEBUG) showLog('Request', 'error:parse data failed', e, {url, options, rawData});
                    if(!isInAppTab && config.zin) return;
                    hasFatal = rawData.includes('Fatal error') || rawData.includes('Uncaught TypeError:') || rawData.startsWith('<!DOCTYPE html');
                    ;
                    data = [{name: hasFatal ? 'fatal' : 'html', data: rawData}];
                }
                if(Array.isArray(data))
                {

                    if(!options.partial && !hasFatal) currentAppUrl = (response && response.url) ? (response.url.split('?zin=')[0].split('&zin=')[0]) : url;
                    let newCacheData = (cacheKey && !hasFatal) ? rawData : null;
                    if(newCacheData && DEBUG)
                    {
                        const parts = newCacheData.split(',["zinDebug:<BEGIN>",');
                        if(parts.length > 1) newCacheData = parts[0] + ']';
                    }
                    cacheHit = !hasFatal && cache && newCacheData === cache.data.data;
                    if(!cacheHit || !localCacheFirst)
                    {
                        renderPageData(data);
                    }
                    else if(DEBUG)
                    {
                        showLog('Request', 'success:skip render with effective caching', {cacheKey, data})
                        renderPageData(data, true);
                    }
                    $(document).trigger('pagerender.app');
                    callCallback('success', [data]);
                    if(cacheKey)
                    {
                        const cacheTime = +response.headers.get('X-Zin-Cache-Time');
                        if(cacheTime)
                        {
                            $.db.setCacheData(cacheKey, {data: newCacheData, url: currentAppUrl, partial: options.partial, selectors: selectors.join(','), clientTime: Date.now()}, cacheTime, 'zinFetch');
                        }
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
                                $(document).trigger('locate.zt', data.load);
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
                    if(data.callback)
                    {
                        const callback = data.callback;
                        if(typeof callback === 'string')
                        {
                            const func = $.runJS(callback);
                            if (typeof func === 'function' && !callback.endsWith(';')) func();
                        }
                        else if(typeof callback === 'object')
                        {
                            const func = $.runJS(callback.name);
                            if (typeof func === 'function')
                            {
                                func.apply(null, Array.isArray(callback.params) ? callback.params : [callback.params]);
                            }
                        }
                    }
                }
            },
            error: (error, type) =>
            {
                if(!ridSet.has(rid))
                {
                    if(DEBUG) showLog('Request', `error:canceled request ${rid} on error`, {url, rid, options});
                    return;
                }
                const data = ajax.data;
                if(!data && !ajax.sendedAgain)
                {
                    ajax.sendedAgain = true;
                    ajax.setting.headers['X-Zin-Debug'] = true;
                    delete ajax.data;
                    delete ajax.error;
                    ajax.send();
                    ajax.canceled = true;
                    return;
                }
                else if(data)
                {
                    showFatalError(data, null, options);
                    ajax.canceled = false;
                }

                if(ajax.canceled) return;
                updatePerfInfo(options, 'requestEnd', {error: error});
                if(type === 'abort') return console.log('[ZIN] ', 'Abord fetch data from ' + url, {type, error});
                if(DEBUG) console.error('[ZIN] ', 'Fetch data failed from ' + url, {type, error});
                if(!data) zui.Messager.show('ZIN: Fetch data failed from ' + url);
                callCallback('error', [data, error]);
            },
            complete: () =>
            {
                if(!options.partial) $('body').removeClass('loading-page');
                if(ajax.canceled) return;
                if(onFinish) onFinish();
                $(document).data('zinCache', null);
                if(options.loadingTarget !== false) toggleLoading(options.loadingTarget || target, false, options.loadingClass);
                callCallback('complete', []);
                $(document).trigger('pageload.app');
                const frameElement = window.frameElement;
                if(frameElement)
                {
                    frameElement.classList.remove('loading');
                    frameElement.classList.add('in');
                }
            }
        });
        ajax.sendedAgain = true; // Disable the request again.
        updatePerfInfo(options, 'requestBegin', {perf: {renderBegin: undefined, renderEnd: undefined}});
        if(DEBUG) showLog('Request', `${ajax.setting.type}:${options.id} ${getUrlID(url)} task ${rid}`, options, {cacheKey, ajax});
        if(currentCode) $.cookie.set('tab', currentCode, {expires: config.cookieLife, path: config.webRoot});
        if(cacheKey)
        {
            $.db.getCache(cacheKey, 'zinFetch').then(localCache =>
            {
                if(!ridSet.has(rid))
                {
                    if(DEBUG) showLog('Request', `error:canceled request ${rid} on load cache`, {url, rid, options});
                    return;
                }
                cache = localCache;
                if(cache && localCacheFirst)
                {
                    ajax.setting.headers['X-Zin-Cache-Time'] = cache.updateTime;
                    if($(document).data('zinCache') !== [cache.key, cache.time].join('#'))
                    {
                        try
                        {
                            const data = $.parseRawData(cache.data.data);
                            if(DEBUG) showLog('Request', 'success:render with cache', {url, cacheKey, data, options});
                            if(!cache.data.partial) currentAppUrl = cache.data.url;
                            renderPageData(data);
                            options.isDiffPage = false;
                            $(document).data('zinCache', [cache.key, cache.time].join('#')).trigger('pagecaheload.app');
                        }
                        catch(error)
                        {
                            if(DEBUG) showLog('Request', 'error:Parse cache data failed', {url, cacheKey, options});
                            $.db.setCacheData(cacheKey, null, cache.time, 'zinFetch');
                        }
                    }
                    else
                    {
                        if(DEBUG) showLog('Request', 'success:skip render data with same cache', {url, cacheKey, options});
                    }
                }
                else
                {
                    if(DEBUG && localCacheFirst) showLog('Request', 'no local cache', {url, cacheKey, options});
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
        let task = fetchTasks.get(id);
        if(task && !options.partial && task.url === url)
        {
            if(DEBUG) showLog('Request', [`success:same request ${task.rid}`, url], {task, options});
            return;
        }

        options = $.extend({}, options, {url: url, selectors: selectors, id: id, isDiffPage: isDiffPage(url), pageID: getUrlID(url)});
        const dispatchTask = () =>
        {
            if(task)
            {
                if(task.xhr)     task.xhr.abort();
                if(task.timerID) clearTimeout(task.timerID);
                if(task.rid)     ridSet.delete(task.rid);
            }
            const rid = $.guid++;
            task = {url: url, selectors: selectors, options: options, rid: rid};
            options.rid = rid;
            fetchTasks.set(id, task);
            ridSet.add(rid);

            task.timerID = setTimeout(() =>
            {
                task.timerID = 0;
                task.xhr = requestContent(options, () =>
                {
                    if(task.xhr)
                    {
                        const runID = task.xhr.getResponseHeader('Xhprof-RunID');
                        if(runID) updateZinbar({id: id, xhprof: `${config.webRoot}xhprof/xhprof_html/index.php?run=${runID}&source=${config.currentModule}_${config.currentMethod}`});
                        task.xhr = null;
                    }
                    fetchTasks.delete(id);
                    ridSet.delete(rid);
                });
            }, options.delayTime || 0);
        };
        if(window.beforeRequestContent)
        {
            const result = window.beforeRequestContent(options, task);
            if(result === false) return;
            if(result instanceof Promise)
            {
                result.then((result) => result && dispatchTask());
                return;
            }
        }
        dispatchTask();
    }

    /** Load an old page. */
    function loadOldPage(url)
    {
        let $page = $('#oldPage');
        const clearTimer = () =>
        {
            const timer = $page.data('timer');
            if(timer)
            {
                clearTimeout(timer);
                $page.data('timer', 0);
            }
        };
        if(!$page.length)
        {
            $page = $('<div/>')
                .append($('<iframe />').attr({name: `app-${currentCode}-old`, frameborder: 'no', scrolling: 'auto', style: 'width:100%;height:100%;'}))
                .attr({id: 'oldPage', class: 'canvas fixed w-full h-full top-0 left-0', style: 'z-index:9999;'})
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
                    oldPageCofnig = iframeWindow.config;
                    if(iframeWindow.$) iframeWindow.$(iframeWindow.document).on('click', () => window.parent.$('body').trigger('click'));
                    clearTimer();
                });
        }

        clearTimer();
        if($page.hasClass('hidden')) $page.addClass('loading').removeClass('hidden');
        const $iframe = $page.find('iframe').removeClass('in').addClass('invisible');
        if($iframe.attr('src') === url && $iframe[0].contentWindow.location.href === url)
        {
            $iframe[0].contentWindow.location.reload();
        }
        else
        {
            $iframe.attr('src', url);
            setTimeout(() =>
            {
                /* Fix firefox not load page when iframe is not ready. */
                if($iframe[0].contentWindow.location.href === 'about:blank') $iframe[0].contentWindow.location.href = url;
            }, 500);
        }
        currentAppUrl = url;
        openedOldPage = url;
        triggerEvent('openOldPage');
        $page.data('timer', setTimeout(() =>
        {
            if($page.hasClass('loading') || $iframe.hasClass('invisible')) $page.trigger('oldPageLoad.app');
            else clearTimer();
        }, 1500));
    }

    /** Hide old page content. */
    function hideOldPage()
    {
        const $page = $('#oldPage');
        if(!$page.length) return;
        $page.addClass('in hidden');
        openedOldPage = null;
        oldPageCofnig = null;
    }

    function getLoadSelector(selector)
    {
        if(!selector) return $('#main').length ? `#configJS,pageCSS/.zin-page-css>*,pageJS/.zin-page-js,hookCode(),title>*,${$('#header').length ? '#heading>*,#navbar>*,#pageToolbar>*,' : ''}#main>*,` : '#configJS,title>*,body>*';
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
            const parts = options.url.split(' ', 2);
            options.url      = parts[0];
            options.selector = parts[1];
        }

        options  = $.extend({url: currentAppUrl, id: options.selector || options.target || 'page'}, options, {selector: getLoadSelector(options.selector)});

        if(window.beforePageLoad)
        {
            const result = window.beforePageLoad(options);
            if(result === false) return;
            if(result) $.extend(options, result);
        }
        if(DEBUG) showLog('Load', getUrlID(options.url), options);
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
            selector = 'pageJS/.zin-page-js,' + selector + ',#mainMenu>*,hookCode()';
            if($('#mainNavbar a > .label').length) selector += ',#mainNavbar>*';
            if($('#moduleMenu').length) selector += ',#moduleMenu>*,.module-menu-header';
            if($('#docDropmenu').length) selector += ',#docDropmenu,.module-menu';
        }
        delete options.selector;
        return loadComponent(target, $.extend({cache: isDiffPage(url), component: 'dtable', url: url, selector: selector, modal: isInModal}, options));
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
        if(((modal.options.url && options.url) || (modal.options.request && options.request)) && options.loadingClass === undefined)
        {
            const lastUrl = $.parseLink(modal.options.url) || $.parseLink(modal.options.request?.url);
            const newUrl  = $.parseLink(options.url) || $.parseLink(options.request?.url);
            if(lastUrl.moduleName === newUrl.moduleName && lastUrl.methodName === newUrl.methodName) options.loadingClass = '';
        }
        if(modal.options.request && options.url) {modal.options.request = options.request}
        modal.render(options).then((result) => {if(result && callback) callback(modal.dialog);});
    }

    function loadTarget(url, target, options)
    {
        options = $.extend({}, options);
        if(typeof target === 'string') options.target = target;
        else if($.isPlainObject(target)) options = $.extend(options, target);
        if(typeof url === 'string') options.url = url;
        else if(typeof url === 'object') options = $.extend(options, url);
        if(!options.target) return loadPage(options);

        let remoteData;
        let loadError;
        target = options.target;
        if(typeof target === 'string' && target[0] !== '#' && target[0] !== '.') target = `#${target}`;
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
        options = $.extend({url: url, selector: selector, method: 'POST', data: data, contentType: false}, options);
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
        return loadPage($.extend({cache: false}, options));
    }

    function reloadPage()
    {
        loadPage({cache: false, url: currentAppUrl, selector: 'body>*,title>*,#configJS'});
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

        if(DEBUG) showLog('Load form', id, {options, data});

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
            cache:         false,
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
                        if('.[#'.includes(name[0]))
                        {
                            const $items         = $data.filter(name);
                            const $oldMatchItems = $oldItems.filter(name);
                            $oldMatchItems.last().after($items);
                            $oldMatchItems.remove();
                            $items.zuiInit();
                        }
                        else
                        {
                            const $items         = $data.filter(`[data-name="${name}"]`);
                            const $oldMatchItems = $oldItems.filter(`[data-name="${name}"]`);
                            if($oldMatchItems.length)
                            {
                                try {$oldMatchItems.replaceWith($items);} catch (_) {}
                            }
                            else
                            {
                                $oldItems.last().after($items);
                            }
                            $items.zuiInit();
                        }
                    });

                    if(options.updateOrders)
                    {
                        const formGrid = $form.zui('formGrid');
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
                    $form.html(info.data);
                    const formGrid = $form.zui('formGrid');
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

    function applyFormData(data, formSelector)
    {
        let formHelper;
        if(window.getPageFormHelper) formHelper = window.getPageFormHelper(formSelector, data);
        if(!formHelper)
        {
            const $form = $(formSelector || '#mainContainer form').filter(function() {return $(this).closest('#formSettingBtn').length === 0;});
            formHelper = zui.zentaoFormHelper($form);
        }

        formHelper.setFormData(data);
    }

    function openPage(url, appCode, options)
    {
        if(DEBUG) showLog('Open page', url, {appCode, options});
        if(!window.config.zin)
        {
            location.href = $.createLink('index', 'app', 'url=' + btoa(url));
            return;
        }
        if(getUrlID(url) === 'index-index') return top.location.href = url;
        return $.apps.openApp(url, $.extend({code: appCode, forceReload: true}, options));
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

        if(url.includes('#'))
        {
            const hash = url.split('#')[1];
            if(hash === 'open-modal' || hash.startsWith('open-modal?'))
            {
                const openModalParams = hash.includes('?') ? hash.split('?')[1] : '';
                const openModalOptions = $.extend({}, {url: url, type: 'ajax'}, options);
                if(openModalParams)
                {
                    const searchParams = new URLSearchParams(openModalParams);
                    for(const key of searchParams.keys()) openModalOptions[key] = searchParams.getAll(key).join(',');
                }
                zui.Modal.open(openModalOptions);
                return;
            }
        }

        if(DEBUG) showLog('Open url', url, options);

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

        return openPage(url, options.app);
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

        const callback = zinCallbacks.onSelectLang;
        if(typeof callback === 'function')
        {
            try { callback(lang); } catch (e) { console.error('[ZIN] onSelectLang callback error:', e); }
        }
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

        const callback = zinCallbacks.onSelectTheme;
        if(typeof callback === 'function')
        {
            try { callback(theme); } catch (e) { console.error('[ZIN] onSelectTheme callback error:', e); }
        }
    }

    /**
     * Select vision.
     * @param {string} vision
     */
    function selectVision(vision)
    {
        $.get($.createLink('my', 'ajaxSwitchVision', 'vision=' + vision), function(result)
        {
            const response = JSON.parse(result);
            if(response.result == 'fail')
            {
                return zui.Messager.error(response.message);
            }
            if(response.result == 'success' && response.load)
            {
                const callback = zinCallbacks.onSelectVision;
                if(typeof callback === 'function')
                {
                    try { callback(getVisions(vision)); } catch (e) { console.error('[ZIN] onSelectVision callback error:', e); }
                }
                window.top.location.href = response.load;
            }
        });
    }

    /**
     * 获取所有界面列表。
     * @param {string} vision
     * @returns {Array<{id: string, label: string, active: boolean}>
     */
    function getVisions(vision)
    {
        const userVisions  = window.getUserVisions();
        const activeVision = vision !== undefined ? vision : config.vision;
        return userVisions.map(key => ({id: key, label: config.visions[key], active: key === activeVision}));
    }

    function fetchMessage(force, fetchUrl)
    {
        let $this     = $('#messageBar');
        let $dropmenu = $("#dropdownMessageMenu");

        if(typeof(force) === 'undefined' || typeof(force) === 'object') force = false;
        if(typeof(fetchUrl) === 'undefined') fetchUrl = $this.attr('data-fetcher');

        setTimeout(function()
        {
            let dropdown = $this.zui('Dropdown');
            let isOpen   = true;
            if(dropdown) isOpen = dropdown.shown;
            if(!isOpen && !force) return;

            $dropmenu.load(fetchUrl);
        }, 100);
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
        if(!$link.length || $link.hasClass('ajax-submit') || $link.attr('download') || $link.attr('data-on') || $link.attr('zui-on') || $link.attr('zui-toggle') || $link.attr('zui-command') || $link.hasClass('show-in-app') || $link.hasClass('not-open-url') || ($link.attr('target') || '')[0] === '_') return;

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

        if(url)
        {
            if(/^(https?|javascript):/.test(url) && !options.app) return;
            if(url[0] === '#')
            {
                if(/firefox/i.test(navigator.userAgent)) e.preventDefault();
                return;
            }
        }
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
            if(app === currentCode && options.load !== 'modal' && options.load !== 'table')
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

    $.extend(window, {registerRender: registerRender, fetchContent: fetchContent, loadTable: loadTable, loadPage: loadPage, postAndLoadPage: postAndLoadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector, toggleLoading: toggleLoading, openUrl: openUrl, openPage: openPage, goBack: goBack, registerTimer: registerTimer, loadModal: loadModal, loadTarget: loadTarget, loadComponent: loadComponent, loadPartial: loadPartial, reloadPage: reloadPage, selectLang: selectLang, selectTheme: selectTheme, selectVision: selectVision, changeAppLang, changeAppTheme: changeAppTheme, waitDom: waitDom, fetchMessage: fetchMessage, setImageSize: setImageSize, showMoreImage: showMoreImage, autoLoad: autoLoad, loadForm: loadForm, showValidateMessage: showValidateMessage, getPageInfo: getPageInfo, getPerfData: getPerfData, applyFormData: applyFormData, zinCallbacks: zinCallbacks, registerZinCallback: registerZinCallback, getVisions: getVisions});
    $.extend($.apps, {openUrl: openUrl, getAppUrl: () => currentAppUrl});
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
                else $(document).trigger('locate.zt', data.cancelled || data.canceled);
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
        const initialState = {url: currentAppUrl, title: document.title};
        window.history.pushState(initialState, initialState.title, initialState.url);
        $(window).on('popstate', function(event)
        {
            const state = event.state;
            if(DEBUG) showLog(state ? state.code : null, 'Popstate', state ? state.url : null, state);
            if(state && state.url) openPage(state.url);
        });
    }

    $(() =>
    {
        if($.apps.theme) changeAppTheme($.apps.theme);

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
            if(!isInAppTab && !zui.store.get('Zinbar:hidden') && zui.dom.isVisible($('#navbar'))) loadCurrentPage();
            if(zinbar)
            {
                $(document).on('inited.zt', (e) =>
                {
                    if(e.target.parentElement.closest('[z-use]')) return;
                    const now = performance.now();
                    if(zinbar.waitZUI && (now - zinbar.waitZUI.time) <= 100)
                    {
                        zinbar.waitZUI.time = now;
                        updatePerfInfo(zinbar.waitZUI, 'renderEnd');
                    }
                    else
                    {
                        zinbar.waitZUI = null;
                    }
                });
            }
        }
    });
}());
