(function()
{
    if (config.skipRedirect || window.skipRedirect) return;

    const parent        = window.parent;
    const currentModule = config.currentModule;
    const currentMethod = config.currentMethod;
    const isIndexPage   = currentModule === 'index' && currentMethod === 'index';

    const isAllowSelfOpen = isIndexPage
        || location.hash === '#_single'
        || /(\?|\&)_single/.test(location.search)
        || currentModule === 'tutorial'
        || currentModule === 'install'
        || currentModule === 'upgrade'
        || (currentModule === 'user'
            && (currentMethod === 'login' || currentMethod === 'deny'))
        || (currentModule === 'file' && currentMethod === 'download')
        || (currentModule === 'my' && currentMethod === 'changepassword')
        || $('body').hasClass('allow-self-open');

    if (parent === window && !isAllowSelfOpen) {
        const shortUrl = location.pathname + location.search + location.hash;
        location.href = $.createLink('index', 'index', `open=${btoa(shortUrl)}`);
        return;
    }
}());

(function()
{
    const config      = window.config;
    const isIndexPage = config.currentModule === 'index' && config.currentMethod === 'index';
    if(isIndexPage) return;

    const DEBUG       = config.debug;
    const currentCode = window.name.substring(4);
    const isInAppTab  = parent.window !== window;
    const fetchTasks  = new Map();
    const startTime   = performance.now();
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
        reloadApp: function(_code, url)
        {
            loadPage(url);
        },
    }, parent.window.$.apps);

    const renderMap =
    {
        html:          updatePageWithHtml,
        body:          (data) => $('body').html(data),
        title:         (data) => document.title = data,
        main:          (data) => $('#main').html(data),
        featureBar:    (data) => $('#featureBar').html(data),
        pageCSS:       (data) => $('#pageCSS').html(data),
        configJS:      (data) => $('#configJS')[0].text = data,
        pageJS:        (data) => $('#pageJS').replaceWith(data),
        activeFeature: (data) => activeNav(data, '#featureBar'),
        activeMenu:    activeNav,
        table:         updateTable,
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
        $('body').empty().append($(`<div class="panel danger shadow-xl mx-auto my-4 rounded-lg" style="max-width: 1000px"><div class="panel-heading"><div class="panel-title font-bold text-lg">Fatal error: ${options.url}</div></div></div>`).append($('<div class="panel-body font-mono"></div>').append(data)));
    }

    function initZinbar()
    {
        if(!DEBUG || isIndexPage) return;
        let $bar = $('#zinbar');
        if($bar.length) return;

        $bar = $('<div id="zinbar"></div>').insertAfter('body');
        zinbar = new zui.Zinbar($bar[0]);
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

    function activeNav(activeID, nav)
    {
        const $nav    = $(nav || '#navbar');
        const $active = $nav.find('.nav-item>a.active');
        if($active.data('id') === activeID) return;
        $active.removeClass('active');
        $nav.find('.nav-item>a[data-id="' + activeID + '"]').addClass('active');
    }

    function updateTable(data)
    {
        const props = data.props;
        const $table = $('#' + props.id).parent();
        if(!$table.length) return;
        const dtable = zui.DTable.get($table[0]);
        if(DEBUG) console.log('[APP] ', 'update table:', {data, props});
        dtable.render(props);
    }

    function renderPartial(info, options)
    {
        if(window.config.onRenderPage && window.config.onRenderPage(info)) return;

        const render = renderMap[info.name];
        if(render) return render(info.data, info, options);

        /* Common render */
        const selector = parseSelector(info.selector);
        if(!selector) return console.warn('[APP] ', 'cannot render partial content with data', info);

        const $target = $(selector.select);
        if(!$target.length) return console.warn('[APP] ', 'cannot find target element with selector', selector);
        if(selector.first) $target = $target.first();
        if(selector.type === 'json')
        {
            const props = info.data.props;
            if(typeof props === 'object')
            {
                const targetData = $target.data();
                const zuiComName = Object.keys(targetData).find(prop => prop.startsWith('zui.'));
                if(zuiComName)
                {
                    const zuiCom = targetData[zuiComName];
                    if(typeof zuiCom === 'object' && typeof zuiCom.render === 'function')
                    {
                        Object.keys(props).forEach(prop =>
                        {
                            const value = props[prop];
                            if(typeof value === 'string' && value.startsWith('RAWJS<')) delete props[prop];
                        });
                        zuiCom.render(props);
                    }
                }
            }
            return;
        }

        if(selector.inner) $target.html(info.data);
        else $target.replaceWith(info.data);
    }

    function renderPage(list, options)
    {
        if(DEBUG) console.log('[APP] ', 'render:', list);
        list.forEach(item => renderPartial(item, options));
        const newState = $.apps.updateApp(currentCode, currentAppUrl, document.title);
        if (newState) historyState = newState;
    }

    function toggleLoading(target, isLoading)
    {
        const $target = $(target);
        const position = $target.css('position');
        if(!['relative', 'absolute', 'fixed'].includes(position)) $target.css('position', 'relative');
        if(!$target.hasClass('load-indicator'))
        {
            $target.addClass('load-indicator');
            setTimeout(toggleLoading.bind(null, target, isLoading), 100);
            return;
        }
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
        const selectors = Array.isArray(options.selector) ? options.selector : options.selector.split(',');
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
                toggleLoading();
            },
            success: (data) =>
            {
                updatePerfInfo(options, 'requestEnd', {dataSize: data.length});
                options.result = 'success';
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
                    data = [{name: data.includes('Fatal error') ? 'fatal' : 'html', data: data}];
                }
                if(options.updateUrl !== false) currentAppUrl = url;
                data.forEach((item, idx) => item.selector = selectors[idx]);
                updatePerfInfo(options, 'renderBegin');
                renderPage(data, options);
                updatePerfInfo(options, 'renderEnd');
                $(document).trigger('pagerender.app');
                if(options.success) options.success(data);
                if(onFinish) onFinish(null, data);
            },
            error: (xhr, type, error) =>
            {
                updatePerfInfo(options, 'requestEnd', {error: error});
                if(type === 'abort') return console.log('[ZIN] ', 'Abord fetch data from ' + url, {xhr, type, error});;
                if(DEBUG) console.error('[ZIN] ', 'Fetch data failed from ' + url, {xhr, type, error});
                zui.Messager.show('ZIN: Fetch data failed from ' + url);
                if(options.error) options.error(data, error);
                if(onFinish) onFinish(error);
            },
            complete: () =>
            {
                toggleLoading(target, false);
                if(options.complete) options.complete();
                $(document).trigger('pageload.app');
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
        else if(!options) options = {};
        if(typeof selector === 'string') options.selector = selector;

        if (!options.selector && options.url.includes(' '))
        {
            const parts = url.split(' ', 2);
            options.url      = parts[0];
            options.selector = parts[1];
        }

        options  = $.extend({url: currentAppUrl, id: options.selector || 'page'}, options);
        if(!options.selector) options.selector = ($('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*,title>*');
        if(!options.id) options.id = options.selector || 'page';

        if(DEBUG) console.log('[APP] ', 'load:', options.url);
        fetchContent(options.url, options.selector, options);
    }

    /**
     * Load dtable content.
     *
     * @param {string} [url]
     * @param {string} [id]
     * @param {Object} [options]
     * @returns
     */
    function loadTable(url, id, options)
    {
        id  = id || $('.dtable').attr('id') || 'dtable';
        loadPage($.extend(
        {
            url: url,
            id: '#' + id,
            target: '#' + id,
            selector: 'table/#' + id + ':type=json&data=props,#featureBar>*'
        }, options));
    }

    function postAndLoadPage(url, data, selector, options)
    {
        loadPage($.extend({url: url, selector: selector, method: 'POST', data, contentType: false}, options));
    }

    function loadCurrentPage(options)
    {
        if(typeof options === 'string') options = {selector: options};
        return loadPage(options);
    }

    function openPage(url, appCode)
    {
        if(DEBUG) console.log('[APP] ', 'open:', url);
        if(!window.config.zin)
        {
            location.href = $.createLink('index', 'app', 'url=' + btoa(url));
            return;
        }
        $.apps.reloadApp(appCode || currentCode, url);
    }

    function onRenderPage(callback)
    {
        window.config.onRenderPage = callback;
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
        else if(target === 'GLOBAL')    target = '';
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
     */
    function openUrl(url, options)
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

        const load = options.load;
        if(typeof load === 'string' || load)
        {
            if(options.id)     delete options.id;
            if(url)            options.url = url;
            if(options.loadId) {options.id = options.loadId; delete options.loadId;}
            if(load)
            {
                if(load === 'table') return loadTable(options.url, options.id, options);
                if(load !== 'APP' && typeof load === 'string') options.selector = load;
                delete options.load;
            }
            return loadPage(options);
        }

        if(typeof options.back === 'string') return goBack(options.back, url);

        openPage(url, options.app);
    }

    /**
     * Parse wg selector
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

    $.extend(window, {registerRender: registerRender, fetchContent: fetchContent, loadTable: loadTable, loadPage: loadPage, postAndLoadPage: postAndLoadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector, onRenderPage: onRenderPage, toggleLoading: toggleLoading, openUrl: openUrl, goBack: goBack});

    /* Transfer click event to parent */
    $(document).on('click', (e) =>
    {
        if(isInAppTab) window.parent.$('body').trigger('click');

        const $link = $(e.target).closest('a,.open-url');
        if(!$link.length || $link.hasClass('ajax-submit') || $link.attr('target') === '_blank') return;

        const options = $link.dataset();
        if(options.toggle || $link.hasClass('not-in-app')) return e.preventDefault();

        const url = options.url || $link.attr('href');
        if(!url || url.startsWith('javascript:') || url.startsWith('#')) return;

        openUrl(url, options);
        e.preventDefault();
    }).on('locate.zt', (_e, data) =>
    {
        if(!data) return;
        if(data === true) return loadCurrentPage();
        if(typeof data === 'string') data = {url: data};

        if(data.confirm)
        {
            return zui.Modal.confirm(data.confirm).then(confirmed =>
            {
                if(confirmed) $(document).trigger('locate.zt', data.confirmed);
                else $(document).trigger('locate.zt', data.cancelled);
            });
        }
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

        if(window.defaultAppUrl)
        {
            loadPage(window.defaultAppUrl);
        }
        else if(DEBUG)
        {
            if(window.zinDebug)
            {
                let requestBegin = startTime;
                if(performance.timing) requestBegin -= ((performance.timing.loadEventStart || Date.now()) - (performance.timing.navigationStart || Date.now()));
                else if(window.zinDebug.trace && window.zinDebug.trace.request) requestBegin -= window.zinDebug.trace.request.timeUsed;
                updatePerfInfo({id: 'page'}, 'renderEnd', {id: 'page', perf: {requestBegin: Math.max(0, requestBegin), requestEnd: startTime, renderBegin: startTime}});
                showZinDebugInfo(window.zinDebug, {id: 'page'});
            }
            if(!isInAppTab) loadCurrentPage();
        }

        /* Compatible with old version */
        if(DEBUG && typeof window.zin !== 'object' && isInAppTab)
        {
            console.log('[ZUI3]', 'Compatible with old version');
            window.jQuery = $;
            const empty = () => {};
            window.adjustMenuWidth = empty;
            window.startCron = empty;
            $.zui = $.extend(function(){console.warn('[ZUI3]', 'The $.zui() is not supported.');}, zui);
            $.initSidebar = empty;
            parent.window.$.apps.openedApps = $.apps.openedApps = $.apps.openedMap;
            parent.window.$.apps.updateUrl = $.apps.updateUrl = empty;
            const ajaxOld = $.ajax;
            window.createLink = $.createLink;
            window.parseLink = $.parseLink;
            $.ajax = function(url, settings)
            {
                ajaxOld.call(this, url, settings);
                const deffered = {};
                const ajaxWarn = function(name)
                {
                    console.warn('[ZUI3]', 'The $.ajax().' + name + '() is not supported.');
                    return deffered;
                };
                $.extend(deffered, {done: ajaxWarn.bind(deffered, 'done'), fail: ajaxWarn.bind(deffered, 'fail'), always: ajaxWarn.bind(deffered, 'always')});
                return deffered;
            };
            $.extend($.fn,
            {
                sortable: function()
                {
                    console.warn('[ZUI3]', 'The $().sortable() is not supported.');
                    return this;
                },
                scroll: function()
                {
                    console.warn('[ZUI3]', 'The $().scroll() is not supported.');
                    return this;
                },
                resize: function()
                {
                    console.warn('[ZUI3]', 'The $().resize() is not supported.');
                    return this;
                },
                table: function()
                {
                    console.warn('[ZUI3]', 'The $().table() is not supported.');
                    return this;
                },
            });
        }
    });
}());
