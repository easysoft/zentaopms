function getForm()
{
    const module = $('#module').val();
    const block  = $('#block').val();
    const url    = $.createLink('block', 'create', 'dashboard='+ dashboard +'&module=' + module + '&block=' + (block ? block : ''));
    loadPage(url, '#blockRow, #paramsRow');
}
