/**
 * 添加模块后重新渲染模块选择器部件。
 * Re-render modulePicker widget after adding module.
 *
 * @param  int    rootID
 * @param  string viewType
 * @access public
 * @return void
 */
renderModulePicker = function(rootID, viewType)
{
    if(config.debug) console.log('[ZIN] Rendering module picker');

    const branch = $('[name=branch]').val() || 0;
    const link   = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + rootID + '&viewtype=' + viewType + '&branch=' + branch + '&rootModuleID=0&returnType=items');
    $.getJSON(link, function(data)
    {
        $('#moduleBox [name=module]').zui('picker').render({items: data});
        $('#moduleBox #manageModule').hide();
    });
}
