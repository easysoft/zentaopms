(function()
{
    if (config.skipRedirect || window.skipRedirect) return;

    const parent        = window.parent;
    const currentModule = config.currentModule;
    const currentMethod = config.currentMethod;
    const isIndexPage   = currentModule === 'index' && currentMethod === 'index';
    const isInAppTab    = parent.window !== window;
    const is18version   = config.zin === 'compatible';

    const selfOpenList = new Set('index|tutorial|install|upgrade|sso|cron|misc|user-login|user-deny|user-logout|user-reset|user-forgetpassword|user-resetpassword|my-changepassword|my-preference|file-read|file-download|file-uploadimages|report-annualdata|misc-captcha|execution-printkanban|traincourse-playvideo'.split('|'));
    const isAllowSelfOpen = isIndexPage
        || location.hash === '#_single'
        || /(\?|\&)_single/.test(location.search)
        || selfOpenList.has(`${currentModule}-${currentMethod}`)
        || selfOpenList.has(currentModule)
        || $('body').hasClass('allow-self-open');

    if(!isInAppTab && !isAllowSelfOpen)
    {
        const shortUrl = location.pathname + location.search + location.hash;
        location.href = $.createLink('index', 'index', `open=${btoa(shortUrl)}`);
        return;
    }

    if(is18version && isInAppTab && parent.$.apps) // 18+zin.
    {
        if(isIndexPage) parent.location.reload();

        var name = window.name;
        if(name.indexOf('app-') === 0)
        {
            $.apps = window.apps = parent.$.apps;
            var appCode = name.substring(4);
            $.appCode = appCode;
            $.apps.updateUrl(appCode, location.href, document.title);
        }
    }
}());

(function()
{
    let config        = window.config;
    const isIndexPage = config.currentModule === 'index' && config.currentMethod === 'index';
    if(isIndexPage) return;

    const DEBUG       = config.debug;
    const is18version = config.zin === 'compatible';
    const currentCode = window.name.substring(4);
    const isInAppTab  = parent.window !== window;
    const fetchTasks  = new Map();
    const startTime   = performance.now();
    const timers      = {timeout: [], interval: []};
    let currentAppUrl = (isInAppTab && !is18version) ? '' : location.href;
    let zinbar        = null;

    $.apps = $.extend(
    {
        currentCode: currentCode,
        updateApp: function(code, url, title)
        {
            if(is18version) return $.apps.updateUrl(code, url, title);
            const state    = typeof code === 'object' ? code : {url: url, title: title};
            const oldState = window.history.state;

            if(title) document.title = title;

            if(oldState && oldState.url === url) return;

            window.history.pushState(state, title, url);
            if(DEBUG) console.log('[APP]', 'update:', {code, url, title});
            return state;
        },
        isOldPage:      () => false,
        reloadApp:      function(_code, url){loadPage(url);},
        openApp:        function(url, options){loadPage(url, options);},
        goBack:         function(){history.go(-1);},
        changeAppsLang: changeAppLang,
        changeAppsTheme: changeAppTheme
    }, parent.window.$.apps);
    $.apps.openedMap = $.apps.openedApps;

    const renderMap =
    {
        html:          updatePageWithHtml,
        body:          (data) => $('body').html(data),
        title:         (data) => document.title = data,
        main:          (data) => $('#main').html(data),
        featureBar:    (data) => $('#featureBar').html(data),
        pageCSS:       (data) => $('style.zin-page-css').html(data),
        pageJS:        updatePageJS,
        configJS:      updateConfigJS,
        activeFeature: (data) => activeNav(data, '#featureBar'),
        activeMenu:    (data) => activeNav(data),
        navbar:        updateNavbar,
        heading:       updateHeading,
        fatal:         showFatalError,
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
        if(!DEBUG || isIndexPage) return;
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

        window.onPageUnmount = null;
        window.beforePageUpdate = null;
        window.afterPageUpdate = null;
        window.onPageRender = null;

        if(timers.interval.length) timers.interval.forEach(clearInterval);
        if(timers.timeout.length) timers.timeout.forEach(clearTimeout);
        timers.interval = [];
        timers.timeout = [];

        $('script.zin-page-js').replaceWith(data);
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

    function updateNavbar(data)
    {
        const $navbar = $('#navbar');
        const $newNav = $(data);
        if($newNav.text().trim() !== $navbar.text().trim() || $newNav.find('.nav-item>a').map((_, element) => element.href).get().join(' ') !== $navbar.find('.nav-item>a').map((_, element) => element.href).get().join(' ')) return $navbar.empty().append($newNav);

        activeNav($newNav.find('.nav-item>a.active').data('id'), $navbar);
    }

    function updateHeading(data)
    {
        const $data = $(data);
        const $heading = $('#heading');
        const $dropmenu = $heading.find('#dropmenu');
        const $nextDropmenu = $data.filter('#dropmenu');
        if($dropmenu.dataset('fetcher') === $nextDropmenu.dataset('fetcher'))
        {
            const $toolbar = $heading.find('.toolbar');
            const $nextToolbar = $data.filter('.toolbar');
            if($nextToolbar.text().trim() !== $toolbar.text().trim()) $toolbar.replaceWith($nextToolbar);
        }
        else
        {
            $heading.html(data);
        }
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
            if(typeof component === 'object' && typeof component.render === 'function') component.render(props);
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
        list.forEach(item => renderPartial(item, options));
        if(!options.partial)
        {
            const newState = $.apps.updateApp(currentCode, currentAppUrl, document.title);
            if (newState) historyState = newState;
        }
    }

    function toggleLoading(target, isLoading)
    {
        const $target = $(target);
        const position = $target.css('position');
        if(!['relative', 'absolute', 'fixed'].includes(position)) $target.css('position', 'relative');
        if(!$target.hasClass('load-indicator')) $target.addClass('load-indicator');
        if(isLoading === undefined) isLoading = !$target.hasClass('loading');
        $target.toggleClass('loading', isLoading);
    }

    /**
     * Request data from remote server
     * @param {Object} options
     * @param {string} options.id
     * @param {string} options.url
     * @param {string} options.selector
     * @param {string} [options.target]
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
        const ajaxOptions =
        {
            url:         url,
            headers:     {'X-ZIN-Options': JSON.stringify($.extend({selector: selectors, type: 'list'}, options.zinOptions)), 'X-ZIN-App': currentCode},
            type:        options.method || 'GET',
            data:        options.data,
            contentType: options.contentType,
            beforeSend: () =>
            {
                updatePerfInfo(options, 'requestBegin');
                if(isDebugRequest) return;
                toggleLoading(target, true);
            },
            success: (data) =>
            {
                updatePerfInfo(options, 'requestEnd', {dataSize: data.length});
                options.result = 'success';
                let hasFatal = false;
                try
                {
                    if(data.includes('RAWJS<'))
                    {
                        const func = new Function(`return ${data.split('"RAWJS<').join('').split('>RAWJS"').join('')}`);
                        data = func();
                    }
                    else
                    {
                        data = JSON.parse(data);
                    }
                }
                catch(e)
                {
                    if(!isInAppTab && config.zin) return;
                    hasFatal = data.includes('Fatal error') || data.includes('Uncaught TypeError:');
                    data = [{name: hasFatal ? 'fatal' : 'html', data: data}];
                }
                if(Array.isArray(data))
                {
                    if(!options.partial && !hasFatal) currentAppUrl = url;
                    data.forEach((item, idx) => item.selector = selectors[idx]);
                    updatePerfInfo(options, 'renderBegin');
                    renderPage(data, options);
                    updatePerfInfo(options, 'renderEnd');
                    $(document).trigger('pagerender.app');
                    if(options.success) options.success(data);
                    if(onFinish) onFinish(null, data);
                }
                else if(data.load)
                {
                    if(data.load === 'table') loadTable();
                    if(data.load === 'login') window.top.location.href = $.createLink('user', 'login', 'referer=' + btoa(url));
                    else if(typeof data.load === 'string') loadPage(data.load);
                    else if(data.load === true) loadCurrentPage();
                    else if(typeof data.load === 'object')
                    {
                        if('back' in data.load)
                        {
                            openUrl(data.load);
                        }
                        else if('confirm' in data.load)
                        {
                            zui.Modal.confirm({message: data.load.confirm, onResult: function(result)
                            {
                                loadPage(result ? data.load.confirmed : data.load.canceled);
                            }});
                        }
                        else if('alert' in data.load)
                        {
                            zui.Modal.alert(data.load.alert);
                            if(data.load.locate)
                            {
                                setTimeout(function(){openUrl(data.load.locate);}, 1500);
                            }
                        }
                        else
                        {
                            loadPage(data.load);
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
                toggleLoading(target, false);
                if(options.complete) options.complete();
                $(document).trigger('pageload.app');
                const frameElement = window.frameElement;
                if(frameElement)
                {
                    frameElement.classList.remove('loading');
                    frameElement.classList.add('in');
                }
            }
        };
        return $.ajax(ajaxOptions);
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
        const id = options.id || selectors;
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
                .attr({id: 'oldPage', class: 'canvas fixed w-full h-full top-0 left-0 load-indicator', style: 'z-index:100;'})
                .insertAfter('body')
                .on('oldPageLoad.app', () =>
                {
                    const frame = window.frameElement;
                    frame.classList.remove('loading');
                    frame.classList.add('in');
                    $page.removeClass('loading').find('iframe').addClass('in');
                    $(document).trigger('pageload.app');
                });
        }
        if($page.hasClass('hidden')) $page.addClass('loading').removeClass('hidden');
        const $iframe = $page.find('iframe').removeClass('in');
        if($iframe.attr('src') === url) $iframe[0].contentWindow.location.reload();
        else $iframe.attr('src', url);
    }

    /** Hide old page content. */
    function hideOldPage()
    {
        const $page = $('#oldPage');
        if(!$page.length) return;
        $page.addClass('in hidden');
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

        if(is18version && !options.partial)
        {
            const currentLink = $.parseLink(currentAppUrl);
            const urlInfo = $.parseLink(options.url);
            if(currentLink.moduleName !== urlInfo.moduleName || currentLink.methodName !== urlInfo.methodName) return $.apps.open(options.url, options.app);
        }

        if ($.apps.isOldPage(options.url)) return loadOldPage(options.url);
        else hideOldPage();

        if(typeof selector === 'string')      options.selector = selector;
        else if(typeof selector === 'object') options = $.extend({}, options, selector);
        if (!options.selector && options.url && options.url.includes(' '))
        {
            const parts = url.split(' ', 2);
            options.url      = parts[0];
            options.selector = parts[1];
        }

        options  = $.extend({url: currentAppUrl, id: options.selector || options.target || 'page'}, options);
        if(!options.selector)
        {
            if($('#main').length)
            {
                options.selector = '#main>*,pageCSS/.zin-page-css>*,pageJS/.zin-page-js,#configJS,title>*,#heading>*,#navbar>*';
            }
            else
            {
                options.selector = 'body>*,title>*,#configJS';
            }
            $.share = {};
        }

        if(DEBUG) console.log('[APP] ', 'load:', options.url);
        fetchContent(options.url, options.selector, options);
    }

    function loadPartial(url, selector, options)
    {
        loadPage($.extend({partial: true, url: url, selector: selector, target: selector}, options));
    }

    /** Load zui component. */
    function loadComponent(target, options)
    {
        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        options = $.extend({url: currentAppUrl, id: target, target: target}, options);
        const $target = $(target);
        if(!$target.length) return loadPage({url: options.url, id: target});

        if($target.closest('.modal').length && options.partial === undefined) options.partial = true;
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
        if(!target)
        {
            const urlInfo = $.parseLink(url);
            target = urlInfo.moduleName ? `table-${urlInfo.moduleName}-${urlInfo.methodName}` : ($('.dtable').attr('id') || 'dtable');
        }
        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        return loadComponent(target, $.extend({component: 'dtable', url: url, selector: `dtable/${target}:component,#featureBar>*`}, options));
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
        options = $.extend(url ? {url: url} : {}, options);
        if(!target) return zui.Modal.open(options);
        else if(target === 'current') target = zui.Modal.query().id;

        if(target[0] !== '#' && target[0] !== '.') target = `#${target}`;
        const modal = zui.Modal.query(target);
        if(!modal) return;
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
            url:         url,
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
        if(options.app) openPage(url, options.app, options);
        else            loadPage(options);
    }

    function loadCurrentPage(options)
    {
        if(typeof options === 'string') options = {selector: options};
        return loadPage($.extend({url: currentAppUrl}, options));
    }

    function reloadPage()
    {
        loadPage({url: currentAppUrl, selector: 'body>*,title>*,#configJS'});
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

    /**
     * Search history and go back to specified path.
     *
     * @param {string} target     Back target, can be app name or module-method path.
     * @param {string} url        Fallback url.
     * @returns {void}
     */
    function goBack(target, url)
    {
        if(!target || target === 'APP' || target === true) target = currentCode;
        else if(target === 'GLOBAL') target = '';

        if(target && target.includes('-'))
        {
            const parts = target.split(',')[0].split('-');
            return loadPage($.createLink(parts[0], parts[1]));
        }
        if(url) return loadPage(url);

        window.history.back();
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

        if(options.confirm)
        {
            return zui.Modal.confirm(options.confirm).then(confirmed =>
            {
                if(!confirmed) return;
                openUrl(url, $.extend({}, options, {confirm: false}), event);
            });
        }

        const load = options.load;
        if(is18version && load !== 'table' && load !== 'modal') return $.apps.open(options.url, options.app); // 18+zin.

        if(typeof load === 'string' || load)
        {
            if(!options.target) options.target = options.loadId;
            if(url) options.url = url;
            if(load)
            {
                if(load === 'table')
                {
                    if(!options.target && event) options.target = $(event.target).closest('.dtable').attr('id');
                    return loadTable(options.url, options.target, options);
                }
                if(load === 'modal')
                {
                    if(!options.target && event) options.target = $(event.target).closest('.modal').attr('id');
                    return loadModal(options.url, options.target, options);
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

    function handleClickLinkIn18(e) // 18+zin.
    {
        var $link = $(e.target).closest('a,.open-url');
        if(!$link.length) return;

        if(!$link.length || $link.hasClass('ajax-submit') || $link.hasClass('not-open-url') || ($link.attr('target') || '')[0] === '_') return;

        const options = $link.dataset();
        const url = options.url || $link.attr('href');
        if(url && (url.indexOf('javascript:') === 0 || url[0] === '#')) return;
        if(options.toggle)
        {
            if(options.toggle === 'iframeModal')
            {
                var modalTrigger = $link.data('zui.modaltrigger');
                if(!modalTrigger)
                {
                    delete options.toggle;
                    if(url) options.url = url;
                    options.type = 'iframe';
                    modalTrigger = new window.parent.$.zui.ModalTrigger(options);
                    $link.data('zui.modaltrigger', modalTrigger);
                }
                modalTrigger.show();
            }
            e.preventDefault();
            return;
        }

        if(typeof options.back === 'string') return goBack(options.back, url);

        if(options.loadId)
        {
            options.target = options.loadId;
            delete options.loadId;
        }
        const $modal = $link.closest('.modal');
        if($modal.length)
        {
            if(!options.load)
            {
                if(!url) return;
                options.load = 'modal';
            }
            if(options.load === 'modal' && !options.target) options.target = $modal.attr('id');
            if(options.load === 'table')
            {
                options.partial = true;
                if(!options.url) options.url = $modal.data('zui.Modal').options.url;
            }
        }
        else
        {
            if(options.load === 'modal' && !options.target) delete options.load;
        }
        if(options.load === 'modal' || options.load === 'table')
        {
            e.preventDefault();
            return openUrl(url, options, e);
        }

        var appCode = options.app;
        if(url)
        {
            if(appCode === 'help') return $.apps.openUrl(url, {app: appCode});
            var urlInfo = $.parseLink(url);
            if(urlInfo.external || (urlInfo.moduleName === 'file' && urlInfo.methodName === 'download')) return;
            if(urlInfo.moduleName === 'index' && urlInfo.methodName === 'index')
            {
                window.location.reload();
                e.preventDefault();
                return;
            }
        }
        else
        {
            if(!appCode) return;
        }
        if(!appCode) appCode = $.apps.getAppCode(url);
        if(!appCode) return;

        $.apps.openUrl(url, {app: appCode});
        e.preventDefault();
    }

    function handleClickIn18(event) // 18+zin.
    {
        handleClickLinkIn18(event);

        var iframe = parent.document.getElementById(window.name);
        if(!iframe) return;
        var iframeContainer = parent.document.getElementById(iframe.name) || iframe;
        if(!iframeContainer) return;

        var customEvent;
        if(typeof Event === 'function') {
            customEvent = new Event(event.type, {bubbles: true});
        } else {
            customEvent = document.createEvent('Event');
            customEvent.initEvent(event.type, true, true);
        }
        iframeContainer.dispatchEvent(customEvent);
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
        parent.selectLang(lang);
    }

    /**
     * Select UI theme.
     * @param {string} theme
     */
    function selectTheme(theme)
    {
        parent.selectTheme(theme);
    }

    $.extend(window, {registerRender: registerRender, fetchContent: fetchContent, loadTable: loadTable, loadPage: loadPage, postAndLoadPage: postAndLoadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector, toggleLoading: toggleLoading, openUrl: openUrl, openPage: openPage, goBack: goBack, registerTimer: registerTimer, loadModal: loadModal, loadTarget: loadTarget, loadComponent: loadComponent, loadPartial: loadPartial, reloadPage: reloadPage, selectLang: selectLang, selectTheme: selectTheme, changeAppLang, changeAppTheme: changeAppTheme, uploadFileByChunk: uploadFileByChunk});
    $.extend($.apps, {openUrl: openUrl});
    $.extend($, {ajaxSendScore: ajaxSendScore, selectLang: selectLang});

    /* Transfer click event to parent */
    $(document).on('click', (e) =>
    {
        if(is18version) return handleClickIn18(e); // 18+zin.
        if(e.defaultPrevented) return;
        if(isInAppTab) window.parent.$('body').trigger('click');

        const $link = $(e.target).closest('a,.open-url');
        if(!$link.length || $link.hasClass('ajax-submit') || $link.hasClass('not-open-url') || ($link.attr('target') || '')[0] === '_' || ($link.is('a') && !$link.attr('href'))) return;

        const options = $link.dataset();
        if(options.toggle) return;

        const url = options.url || $link.attr('href');
        const $modal = $link.closest('.modal');
        if(options.loadId)
        {
            options.target = options.loadId;
            delete options.loadId;
        }
        if($modal.length)
        {
            if(!options.load)
            {
                if(!url) return;
                options.load = 'modal';
            }
            if(options.load === 'modal' && !options.target) options.target = $modal.attr('id');
            if(options.load === 'table')
            {
                options.partial = true;
                if(!options.url) options.url = $modal.data('zui.Modal').options.url;
            }
        }
        else
        {
            if(options.load === 'modal' && !options.target) delete options.load;
        }
        if(url && (/^(https?|javascript):/.test(url) || url.startsWith('#'))) return;
        if(!url && $link.is('a') && !options.back && !options.load) return;

        openUrl(url, options, e);
        e.preventDefault();
    }).on('locate.zt', (_e, data) =>
    {
        if(!data) return;
        if(data === true) return loadCurrentPage();
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

        if(data.confirm)
        {
            return zui.Modal.confirm(data.confirm).then(confirmed =>
            {
                if(confirmed) $(document).trigger('locate.zt', data.confirmed);
                else $(document).trigger('locate.zt', data.cancelled);
            });
        }
        if(data.load) return openUrl(data);
        if(data.app) return openPage(data.url + (data.selector ? (' ' + data.selector) : ''), data.app);
        loadPage(data.url, data.selector);
    });

    if(!isInAppTab)
    {
        $(window).on('popstate', function(event)
        {
            const state = event.state;
            if(DEBUG) console.log('[APP]', 'popstate:', state);
            openPage(state.url);
        });
    }

    $(() =>
    {
        initZinbar();

        if(window.defaultAppUrl) loadPage(window.defaultAppUrl);
        if(isInAppTab)
        {
            const frameElement = window.frameElement;
            if(frameElement && parent.window.$) parent.window.$(frameElement).trigger('ready.app');
        }

        if(is18version) // 18+zin.
        {
            if(window.afterPageUpdate) window.afterPageUpdate($('body'));

            if(isInAppTab && parent.$.apps)
            {
                var windowName = window.name;
                if(windowName && windowName.startsWith('app-'))
                {
                    var appCode = windowName.substring(4);
                    var currentApp = parent.$.apps.openedApps[appCode];
                    if(currentApp) currentApp.$app.removeClass('loading');
                }
            }
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
            if(!isInAppTab && !zui.store.get('Zinbar:hidden')) loadCurrentPage();
        }
    });

    const getChunks = (file, chunkSize) => {
        const chunks = [];
        let start = 0;
        let end = Math.min(chunkSize, file.size);

        while (start < end)
        {
            chunks.push(file.slice(start, end));
            start = end;
            end = Math.min(start + chunkSize, file.size);
        }

        return chunks;
    };

    const uploadChunk = (url, chunk, headers) => {
        return fetch(url, {
            method: 'POST',
            body: chunk,
            headers,
        }).then(response => {if(!response.ok) throw new Error('upload file failed');});
    }

    function uploadFileByChunk(url, file, chunkSize = 1024 * 1024, onProgress = null)
    {
        const chunks = getChunks(file, chunkSize);
        let i = 0;

        return new Promise((resolve, reject) => {
            const uploadNextChunk = () => {
                if(i >= chunks.length)
                {
                    if(typeof onProgress === 'function') onProgress(1);
                    resolve();
                }

                const headers = {
                    'X-Chunk-Index': chunkIndex,
                    'X-Total-Chunks': totalChunks,
                };
                uploadChunk(url, chunks[i], headers)
                    .then(() => {
                        i++;
                        if(typeof onProgress === 'function') onProgress(i / chunks.length);
                        uploadNextChunk();
                    })
                    .catch(reject);
            };

            uploadNextChunk();
        });
    };
}());
