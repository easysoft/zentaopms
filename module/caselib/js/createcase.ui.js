/**
 * 加载用例库的模块。
 * Load modules of the caselib.
 */
function loadModules(e)
{
    const libID         = $(e.target).val();
    const moduleID      = $('input[name=module]').val();
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=items');
    $.getJSON(getModuleLink, function(modules)
    {
        if(modules)
        {
            const $modulePicker = $('input[name=module]').zui('picker');
            $modulePicker.render({items: modules});
            $modulePicker.$.setValue(moduleID);
        }
    });
}
