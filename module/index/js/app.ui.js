const debug = config.debug;
const currentAppCode = window.name.substring(4);
let currentAppUrl = defaultUrl;
$.apps = $.extend({currentCode: currentAppCode}, parent.window.$.apps);

function activeNavbar(activeID)
{
    const $active = $('#navbar .nav-item>a.active');
    if($active.data('id') === activeID) return;
    $active.removeClass('active');
    $('#navbar .nav-item>a[data-id="' + activeID + '"]').addClass('active');
}

function renderPage(list)
{
    if(debug) console.log('[APP] ', 'update:', list);
    list.forEach((item) =>
    {
        const name = item.name;
        const data = item.data;
        if(name === 'body')            $('body').html(data);
        else if(name === 'title')      {document.title = data;}
        else if(name === '#main')      $('#main').html(data);
        else if(name === '#pageCSS')   $('#pageCSS').html(data);
        else if(name === '#configJS')  $('#configJS')[0].text = data;
        else if(name === '#pageJS')    $('#pageJS').replaceWith(data);
        else if(name === 'activeMenu') activeNavbar(data);
        console.log('[APP] ', 'update:', {name, data});
    });
    $.apps.updateApp(currentAppCode, currentAppUrl, document.title);
}

function loadTable(id)
{

}

window.loadPage = function loadPage(url, callback)
{
    url = url || defaultUrl;
    if(debug) console.log('[APP] ', 'load:', url);
    const selector = $('#main').length ? '#main>*,#pageCSS>*,#pageJS,#configJS>*,title>*,activeMenu()' : 'body>*';
    $.ajax(
    {
        url:      url,
        dataType: 'json',
        headers:  {'X-ZIN-Options': JSON.stringify({selector: selector, type: 'list'})},
        beforeSend: () =>
        {
            $('body').addClass('page-loading');
        },
        success: (data) =>
        {
            currentAppUrl = url;
            renderPage(data);
            // $(selector).html(data);
            if(callback) callback(data);
        },
        error: () =>
        {
            zui.Messager.show('ZIN: Load page failed from ' + url);
        },
        complete: () =>
        {
            $('body').removeClass('page-loading');
        }
    });
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
    openPage(url);
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
