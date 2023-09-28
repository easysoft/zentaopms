window.toggleSearchForm = function(moduleName, formName, open, trigger)
{
    typeof open == 'undefined' ? $('body').toggleClass('show-search-form') : $('body').toggleClass('show-search-form', open);

    const $body = $('body');
    const show  = $body.hasClass('show-search-form');
    if(!show) return;

    const isCustomFormName = (typeof formName != 'undefined' && formName != '');
    if(typeof moduleName == 'undefined' || moduleName == '') moduleName = config.currentModule;

    let $form = $body.find('#searchFormPanel[data-module="' + moduleName + '"]');
    if(isCustomFormName) $form = $body.find('#' + formName);
    if(!$form.length)
    {
        $form = $('<div id="searchFormPanel" data-module="' + moduleName + '"></div>');
        const $mainCell = $body.find('.main-content-cell');
        if($mainCell.length) $mainCell.prepend($form);
    }
    if(!$form.data('loaded'))
    {
        const url = $.createLink('search', config.zin === 'compatible' ? 'buildZinForm' : 'buildForm', 'module=' + moduleName + '&fields=&params=&actionURL=&queryID=0&formName=' + formName);
        $.get(url, html =>
        {
            $form.html(html).data('loaded', true);
        });
    }
};
