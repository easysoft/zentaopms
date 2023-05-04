function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const code   = field == 'module' ? '' : $('#code').val();
    const url    = $.createLink('block', 'edit', 'blockID=' + blockID + '&module=' + module + '&code=' + (code ? code : ''));
    loadPage(url, '#codesRow, #paramsRow');
}

function onParamsTypeChange(event)
{
    const lang  = config.clientLang;
    const $code = document.querySelector('#code');

    if($('#module').val() == 'scrumtest' && $('#paramstype').val() != 'all')
    {
        $('#title').val($code.options[$code.selectedIndex].text);
    }
    else
    {
        const $paramstype = document.querySelector('#paramstype');
        let blockTitle    = '';

        if(lang.indexOf('zh') >= 0)
        {
            blockTitle = $paramstype.options[$paramstype.selectedIndex].text + of + $code.options[$code.selectedIndex].text;
        }
        else
        {
            blockTitle = $code.options[$code.selectedIndex].text + of + $paramstype.options[$paramstype.selectedIndex].text;
        }

        $('#title').val(blockTitle);
    }
}
