window.afterPageUpdate = function(target, info)
{
    if(info.name !== 'featureBar') return;
    const $searchToggle = $(target).find('.search-form-toggle');
    if(!$searchToggle.length)
    {
        $searchToggle.closest('.show-search-form').removeClass('show-search-form');
        $('.search-form[data-module="trash"]').remove();
        return;
    }

    if(!$searchToggle.parent().hasClass('active')) zui.updateSearchForm('trash');
}
