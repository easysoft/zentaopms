(function(){
    let debug         = true;
    const currentCode = window.name.substring(4);
    const defaultUrl  = window.defaultAppUrl;
    const isInAppTab  = parent.window !== window;
    let currentAppUrl = defaultUrl;

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
            if(debug) console.log('[APP]', 'update:', {code, url, title});
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
        if(debug && Array.isArray(data) && data.length) console.log('[ZIN]  errors:', data);
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
        if(debug) console.log('[ZIN] ', window.zin);
        if(debug) zui.Messager.show({content: 'ZIN: load an old page.', close: false});
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
        if(debug) console.log('[APP] ', 'update table:', {data, props});
        dtable.render(props);
    }

    function renderPartial(info)
    {
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
        if(debug) console.log('[APP] ', 'render:', list);
        list.forEach(renderPartial);
        $.apps.updateApp(currentCode, currentAppUrl, document.title);
    }

    function fetchZinData(url, selector, options)
    {
        options = typeof options === 'function' ? {success: options} : (options || {});

        const target  = options.target || 'body';
        const zinOptions = options.zinOptions;
        selector = Array.isArray(selector) ? selector : selector.split(',');
        if(debug) selector.push('zinErrors()');
        $.ajax(
        {
            url:      url,
            headers:  {'X-ZIN-Options': JSON.stringify($.extend({selector: selector, type: 'list'}, zinOptions)), 'X-ZIN-App': currentCode},
            beforeSend: () => $(target).addClass('loading'),
            success: (data) =>
            {
                try{data = JSON.parse(data);}catch(e){data = [{name: 'html', data: data}];}
                if(options.updateUrl !== false) currentAppUrl = url;
                data.forEach((item, idx) => item.selector = selector[idx]);
                renderPage(data);
                $(document).trigger('pagerender.app');
                if(options.success) options.success(data);
            },
            error: (e) =>
            {
                zui.Messager.show('ZIN: Fetch data failed from ' + url);
                if(options.error) options.error(data);
            },
            complete: () =>
            {
                $(target).removeClass('loading');
                if(options.complete) options.complete();
                $(document).trigger('pageload.app');
            }
        });
    }

    function loadTable(url, id)
    {
        url = url || currentAppUrl;
        id = id || $('.dtable').attr('id') || 'dtable';
        if(!id) return;

        fetchZinData(url, 'table/#' + id + ':type=json&data=props,#featureBar>*');
    }

    function loadPage(url, selector)
    {
        url = url || currentAppUrl || defaultUrl;
        if(debug) console.log('[APP] ', 'load:', url);
        selector = selector || ($('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*,title>*');
        fetchZinData(url, selector);
    }

    function loadCurrentPage(selector)
    {
        return loadPage(currentAppUrl, selector);
    }

    function openPage(url)
    {
        if(debug) console.log('[APP] ', 'open:', url);
        if(!window.config.zin)
        {
            location.href = $.createLink('index', 'app', 'url=' + btoa(url));
            return;
        }
        $.apps.reloadApp(currentCode, url);
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

    $.extend(window, {fetchZinData: fetchZinData, loadTable: loadTable, loadPage: loadPage, loadCurrentPage: loadCurrentPage, parseSelector: parseSelector});

    /* Transfer click event to parent */
    $(document).on('click', (e) =>
    {
        if(isInAppTab) window.parent.$('body').trigger('click');

        const $a = $(e.target).closest('a');
        if(!$a.length || $a.data('toggle') || $a.hasClass('not-in-app') || $a.attr('target') === '_blank') return;

        const url = $a.attr('href');
        if(!url || url.startsWith('javascript') || url.startsWith('#')) return;

        const loadTarget = $a.data('load');
        if(loadTarget === 'table') loadTable(url);
        else openPage(url);
        e.preventDefault();
    });

    if(defaultUrl && defaultUrl !== ['$', '{DEFAULT_URL}'].join('')) loadPage();

    if(!isInAppTab)
    {
        $(window).on('popstate', function(event)
        {
            const state = event.state;
            if(debug) console.log('[APP]', 'popstate:', state);
            openPage(state.url);
        });
    }

    $(() =>
    {
        debug = window.config.debug;

        /* Compatible with old version */
        if(debug && typeof window.zin !== 'object' && isInAppTab)
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
