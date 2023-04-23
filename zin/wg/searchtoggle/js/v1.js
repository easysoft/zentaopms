window.toggleSearchForm = function()
{
    const $body = $('body').toggleClass('show-search-form');
    const show = $body.hasClass('show-search-form');
    zui.bus.emit('searchform.toggle', {show: show});
    if(!show) return;

    let $form = $body.find('#searchFormPanel');
    if(!$form.length) $form = $('<div id="searchFormPanel"></div>').insertAfter('#mainMenu');
    if(!$form.data('loaded'))
    {
        const url = $.createLink('search', 'buildForm', 'module=' + config.currentModule);
        fetch(url).then(response => response.text()).then(html =>
        {
            $form.html(html).data('loaded', true);
            zui.bus.emit('searchform.loaded');
        });
    }
};
