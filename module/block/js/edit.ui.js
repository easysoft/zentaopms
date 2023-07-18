function getForm(event)
{
    const $target = $(event.target).closest('a,input');
    const field   = $target.attr('name');
    const module  = field == 'code' ? $('#blockEditForm #module').val() : $target.attr('data-module');
    const code    = field == 'code' ? $target.val() : '';
    const url     = $.createLink('block', 'edit', 'blockID=' + blockID + '&module=' + module + '&code=' + (code ? code : ''));
    loadPartial(url, '#blockCreateForm #codesRow, #blockCreateForm #paramsRow, #blockCreateForm #module');

    const $nav = $('#blockEditForm .block-modules-nav');
    $nav.find('.nav-item>.active').removeClass('active');
    $nav.find(`.nav-item>a[data-module="${module}"]`).addClass('active');
}

/**
 * Updates the blockTitle when the type is changed.
 *
 * @return void
 */
function onParamsTypeChange()
{
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
