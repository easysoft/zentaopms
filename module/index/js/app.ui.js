const debug = config.debug;
const currentAppCode = window.name.substring(4);
let currentAppUrl = defaultUrl;
$.apps = $.extend({currentCode: currentAppCode}, parent.window.$.apps);

const renderMap =
{
    body:          (data) => $('body').html(data),
    title:         (data) => document.title = data,
    'main':        (data) => $('#main').html(data),
    'featureBar':  (data) => $('#featureBar').html(data),
    'pageCSS':     (data) => $('#pageCSS').html(data),
    'configJS':    (data) => $('#configJS')[0].text = data,
    'pageJS':      (data) => $('#pageJS').replaceWith(data),
    activeFeature: (data) => activeNav(data, '#featureBar'),
    activeMenu:    activeNav,
    table:         updateTable
};

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
        dataType: 'json',
        headers:  {'X-ZIN-Options': JSON.stringify($.extend({selector: selector, type: 'list'}, zinOptions))},
        beforeSend: () => $(target).addClass('loading'),
        success: (data) =>
        {
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
    id = id || $('.dtable').attr('id') || 'dtable';
    if(!id) return;

    fetchZinData(url, 'table/#' + id + ':type=json&data=props,#featureBar>*');
}

window.loadPage = function loadPage(url)
{
    url = url || defaultUrl;
    if(debug) console.log('[APP] ', 'load:', url);
    const selector = $('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*,title>*';
    fetchZinData(url, selector);
}

function openPage(url)
{
    if(debug) console.log('[APP] ', 'open:', url);
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
