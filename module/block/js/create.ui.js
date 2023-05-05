function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const code   = field == 'module' ? '' : $('#code').val();
    const url    = $.createLink('block', 'create', 'dashboard=' + dashboard + '&module=' + module + '&code=' + (code ? code : ''));
    loadPage(url, '#codesRow, #paramsRow');
}

/**
 * Get block title.
 *
 * @return void
 */
function onParamsTypeChange()
{
    const lang  = config.clientLang;
    const $code = $('#code')[0];

    /* 在项目仪表盘创建的待测版本区块时，直接取类型名称为该区块的标题。*/
    /* When creating a test version block in the project dashboard, directly take the name of the block of type.*/
    if($('#module').val() == 'scrumtest' && $('#paramstype').val() != 'all')
    {
        $('#title').val($code.options[$code.selectedIndex].text);
        return;
    }

    const $paramstype = $('#paramstype')[0];
    let blockTitle = '';

    /* Chinese attributives come first, while other language items have attributives placed after. */
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
