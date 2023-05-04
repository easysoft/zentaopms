function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const code   = field == 'module' ? '' : $('#code').val();
    const url    = $.createLink('block', 'create', 'dashboard='+ dashboard +'&module=' + module + '&code=' + (code ? code : ''));
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

        if(lang.indexOf('zh') >= 0)
        {
            const blockTitle  = $paramstype.options[$paramstype.selectedIndex].text + of + $code.options[$code.selectedIndex].text;
        }
        else
        {
            const blockTitle  = $code.options[$code.selectedIndex].text + of + $paramstype.options[$paramstype.selectedIndex].text;
        }

        $('#title').val(blockTitle);
    }
}
