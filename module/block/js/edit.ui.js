function getForm(event)
{
    const field  = $(event.target).attr('id');
    const module = $('#module').val();
    const code   = field == 'module' ? '' : $('#code').val();
    const url    = $.createLink('block', 'edit', 'blockID=' + blockID + '&module=' + module + '&code=' + (code ? code : ''));
    loadPage(url, '#codesRow, #paramsRow');
}

/**
 * Updates the blockTitle when the type is changed.
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

    $('#title').val(blockTitle.replace('%1$s', $paramstype.options[$paramstype.selectedIndex].text).replace('%2$s', $code.options[$code.selectedIndex].text));
}
