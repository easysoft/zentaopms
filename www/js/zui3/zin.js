(function(){
    let DEBUG         = true;
    const currentCode = window.name.substring(4);
    const isInAppTab  = parent.window !== window;
    const fetchTasks  = new Map();
    let currentAppUrl = '';

    $.apps = $.extend(
    {
        currentCode: currentCode,
        updateApp: function(code, url, title)
        {
            const state    = typeof code === 'object' ? code : {url: url, title: title};
            const oldState = window.history.state;

            if(title) document.title   = title;

            if(oldState && oldState.url === url) return;

            window.history.pushState(state, title, url);
            if(DEBUG) console.log('[APP]', 'update:', {code, url, title});
        },
        reloadApp: function(code, url)
        {
            loadPage(url);
        }
    }, parent.window.$.apps);

    const renderMap =
    {
        html:          updatePageWithHtml,
        body:          (data) => $('body').html(data),
        title:         (data) => document.title = data,
        'main':        (data) => $('#main').html(data),
        'featureBar':  (data) => $('#featureBar').html(data),
        'pageCSS':     (data) => $('#pageCSS').html(data),
        'configJS':    (data) => $('#configJS')[0].text = data,
        'pageJS':      (data) => $('#pageJS').replaceWith(data),
        activeFeature: (data) => activeNav(data, '#featureBar'),
        activeMenu:    activeNav,
        table:         updateTable,
        zinErrors:     showZinErrors
    };

    function showZinErrors(data)
    {
        if(DEBUG && Array.isArray(data) && data.length) console.log('[ZIN]  errors:', data);
    }

    function updatePageWithHtml(data)
    {
        const html = [];
        const skipTags = new Set(['SCRIPT', 'META']);
        $(data).each(function(idx, node)
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
        Object.keys(props).forEach(prop =>
        {
            const value = props[prop];
            if(typeof value === 'string' && value.startsWith('RAWJS<')) delete props[prop];
        });
        if(DEBUG) console.log('[APP] ', 'update table:', {data, props});
        dtable.render(props);
    }

    function renderPartial(info)
    {
        if(window.config.onRenderPage && window.config.onRenderPage(info)) return;

        const render = renderMap[info.name];
        if(render) return render(info.data);

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

    function renderPage(list)
    {
        if(DEBUG) console.log('[APP] ', 'render:', list);
        list.forEach(renderPartial);
        $.apps.updateApp(currentCode, currentAppUrl, document.title);
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
     * @param {string} options.selectors
     * @param {string} [options.target]
     * @param {{selector: string, type: string}} [options.zinOptions]
     * @param {function} [options.success]
     * @param {function} [options.error]
     * @param {function} [options.complete]
     * @param {function} [onFinish]
     */
    function requestContent(options, onFinish)
    {
        const target    = options.target || '#main';
        const selectors = Array.isArray(options.selectors) ? options.selectors : options.selectors.split(',');
        const url       = options.url;
        return $.ajax(
        {
            url:      url,
            headers:  {'X-ZIN-Options': JSON.stringify($.extend({selector: selectors, type: 'list'}, options.zinOptions)), 'X-ZIN-App': currentCode},
            beforeSend: () => toggleLoading(target, true),
            success: (data) =>
            {
                try{data = JSON.parse(data);}catch(e){data = [{name: 'html', data: data}];}
                if(options.updateUrl !== false) currentAppUrl = url;
                data.forEach((item, idx) => item.selector = selectors[idx]);
                renderPage(data);
                $(document).trigger('pagerender.app');
                if(options.success) options.success(data);
                if(onFinish) onFinish(null, data);
            },
            error: (xhr, type, error) =>
            {
                if(type === 'abort') return console.log('[ZIN] ', 'Abord fetch data from ' + url, {xhr, type, error});;
                if(DEBUG) console.error('[ZIN] ', 'Fetch data failed from ' + url, {xhr, type, error});
                zui.Messager.show('ZIN: Fetch data failed from ' + url);
                if(options.error) options.error(data);
                if(onFinish) onFinish(error);
            },
            complete: () =>
            {
                toggleLoading(target, false);
                if(options.complete) options.complete();
                $(document).trigger('pageload.app');
            }
        });
    }

    function fetchContent(url, selectors, options)
    {
        if(typeof url === 'object')
        {
            options = url;
            url = options.url;
            selectors = options.selectors;
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
                task.xhr = null;
                fetchTasks.delete(id);
            });
        }, options.delayTime || 0);
    }

    function loadTable(url, id)
    {
        url = url || currentAppUrl;
        id = id || $('.dtable').attr('id') || 'dtable';
        if(!id) return;

        fetchContent(url, 'table/#' + id + ':type=json&data=props,#featureBar>*', {id: '#' + id, target: '#' + id});
    }

    function loadPage(url, selector, id)
    {
        url = url || currentAppUrl;
        if (!selector && url.includes(' ')) {
            const parts = url.split(' ', 2);
            url = parts[0];
            selector = parts[1];
        }
        if(DEBUG) console.log('[APP] ', 'load:', url);
        id = id || selector || 'page';
        if(!selector)
        {
            selector = ($('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*,title>*');
            if(DEBUG) selector += ',zinErrors()';
        }
        fetchContent(url, selector, id);
    }

    function loadCurrentPage(selector)
    {
        return loadPage(currentAppUrl, selector);
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
            else if(result.tag) result.name = result.tag;
            else               result.name = selector;
        }
        result.select = [result.tag, result.id.length ? '#' + result.id : '', result.class.length ? '.' + result.class.join('.') : ''].join('');

        return result;
    }

    $.extend(window, {fetchContent: fetchContent, loadTable: loadTable, loadPage: loadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector, onRenderPage: onRenderPage, toggleLoading: toggleLoading});

    /* Transfer click event to parent */
    $(document).on('click', (e) =>
    {
        if(isInAppTab) window.parent.$('body').trigger('click');

        const $a = $(e.target).closest('a');
        if(!$a.length || $a.attr('target') === '_blank') return;
        if($a.data('toggle') || $a.hasClass('not-in-app')) return e.preventDefault();

        const url = $a.attr('href');
        if(!url || url.startsWith('javascript') || url.startsWith('#')) return;

        const loadTarget = $a.data('load');
        if(loadTarget === 'table') loadTable(url);
        else openPage(url);
        e.preventDefault();
    }).on('zui.locate', (e, data) =>
    {
        if(!data) return;
        if(typeof data === 'string') data = {url: data};
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
        if(window.defaultAppUrl) loadPage(window.defaultAppUrl);

        DEBUG = window.config.debug;

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
