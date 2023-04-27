function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const block  = field == 'module' ? '' : $('#block').val();
    const url    = $.createLink('block', 'edit', 'blockID=' + blockID + '&module=' + module + '&block=' + (block ? block : ''));
    loadPage(url, '#blockRow, #paramsRow');
}
