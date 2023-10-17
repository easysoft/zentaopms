function getForm(event)
{
    const $target = $(event.target).closest('a,input');
    const field   = $target.attr('name');
    const module  = field == 'code' ? $('#blockCreateForm #module').val() : $target.attr('data-module');
    const code    = field == 'code' ? $target.val() : '';
    const url     = $.createLink('block', 'create', 'dashboard=' + dashboard + '&module=' + module + '&code=' + (code ? code : ''));
    loadPartial(url, '#blockCreateForm #codesRow, #blockCreateForm #paramsRow, #blockCreateForm #module');

    const $nav = $('#blockCreateForm .block-modules-nav');
    $nav.find('.nav-item>.active').removeClass('active');
    $nav.find(`.nav-item>a[data-module="${module}"]`).addClass('active');
}

/**
 * Updates the blockTitle when the type is changed.
 *
 * @return void
 */
function changeType()
{
    const lang = config.clientLang;
    const code = $('[name="code"]').zui('picker').$.state.selections[0].text;
    const type = $('[name="params[type]"]').zui('picker').$.state.selections[0].text;

    /* 在项目仪表盘创建的待测版本区块时，直接取类型名称为该区块的标题。*/
    /* When creating a test version block in the project dashboard, directly take the name of the block of type.*/
    if($('#module').val() == 'scrumtest' && $('#paramstype').val() != 'all')
    {
        $('#title').val(code);
        return;
    }

    $('#title').val(blockTitle.replace('%1$s', type).replace('%2$s', code));
}
