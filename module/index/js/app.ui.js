const debug = config.debug;
const currentAppCode = window.name.substring(4);
let currentAppUrl = defaultUrl;
$.apps = $.extend({currentCode: currentAppCode}, parent.window.$.apps);

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

function renderPage(list)
{
    if(debug) console.log('[APP] ', 'render:', list);
    list.forEach((item) =>
    {
        const render = renderMap[item.name];
        if(render) render(item.data);
    });
    $.apps.updateApp(currentAppCode, currentAppUrl, document.title);
}

window.fetchZinData = function fetchZinData(url, selector, options)
{
    options = typeof options === 'function' ? {success: options} : (options || {});

    const target  = options.target || 'body';
    const zinOptions = options.zinOptions;
    $.ajax(
    {
        url:      url,
        headers:  {'X-ZIN-Options': JSON.stringify($.extend({selector: selector + ',zinErrors()', type: 'list'}, zinOptions)), 'X-ZIN-App': currentAppCode},
        beforeSend: () => $(target).addClass('loading'),
        success: (data) =>
        {
            try{data = JSON.parse(data);}catch(e){data = [{name: 'html', data: data}];}
            if(options.updateUrl !== false) currentAppUrl = url;
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

window.loadTable = function loadTable(url, id)
{
    url = url || currentAppUrl;
    id = id || $('.dtable').attr('id') || 'dtable';
    if(!id) return;

    fetchZinData(url, 'table/#' + id + ':type=json&data=props,#featureBar>*');
}

window.loadPage = function loadPage(url, selector)
{
    url = url || defaultUrl;
    if(debug) console.log('[APP] ', 'load:', url);
    selector = selector || ($('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*,title>*');
    fetchZinData(url, selector);
}

function openPage(url)
{
    if(debug) console.log('[APP] ', 'open:', url);
    if(!window.config.zin)
    {
        location.href = $.createLink('index', 'app', 'url=' + btoa(url));
        return;
    }
    $.apps.reloadApp(currentAppCode, url);
}

/* Transfer click event to parent */
$(document).on('click', (e) =>
{
    window.parent.$('body').trigger('click');

    const $a = $(e.target).closest('a');
    if(!$a.length || $a.data('toggle') || $a.hasClass('not-in-app') || $a.attr('target') === '_blank') return;

    const url = $a.attr('href');
    if(!url) return;

    const loadTarget = $a.data('load');
    if(loadTarget === 'table') loadTable(url);
    else openPage(url);
    e.preventDefault();
});

if(defaultUrl !== ['$', '{DEFAULT_URL}'].join('')) loadPage();

/* Compatible with old version */
if(debug && typeof window.zin !== 'object')
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
