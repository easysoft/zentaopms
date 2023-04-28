function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const code   = field == 'module' ? '' : $('#code').val();
    const url    = $.createLink('block', 'create', 'dashboard='+ dashboard +'&module=' + module + '&code=' + (code ? code : ''));
    loadPage(url, '#codeRow, #paramsRow');
}
