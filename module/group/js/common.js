/**
 * Load modules and packages by view.
 *
 * @param  string $viewName
 * @access public
 * @return void
 */
function loadModuleAndPackage(viewName)
{
    if(viewName == '')
    {
        $('#module').data('zui.picker').setValue('');
        $('#package').data('zui.picker').setValue('');
    }
    loadModules(viewName);
    loadPackages(viewName, 'view');
}

/**
 * Load modules by view.
 *
 * @param  string $viewName
 * @access public
 * @return void
 */
function loadModules(viewName)
{
    $.get(createLink('group', 'ajaxGetPrivModules', "view=" + viewName), function(data)
    {
        $('#module').next('.picker').remove();
        $('#module').replaceWith(data);
        $('#module').picker();
    });
}

/**
 * Load pakcages by view or by module.
 *
 * @param  string $viewName
 * @access public
 * @return void
 */
function loadPackages(object, objectType)
{
    if(object == packageModulePairs[$('#package').val()]) return false;
    if(object == '') $('#package').data('zui.picker').setValue('');
    $.get(createLink('group', 'ajaxGetPrivPackages', "object=" + object + '&objectType=' + objectType), function(data)
    {
        $('#package').replaceWith(data);
        $('#package').next('.picker').remove();
        $('#package').picker();
    });
    if(objectType == 'module' && object != '')
    {
        if($('#view').val() != moduleViewPairs[object]) $('#view').val(moduleViewPairs[object]).trigger('chosen:updated');
        $('#view').closest('td').find('.text-danger.help-text').remove();
        $('#view').closest('td').find('.has-error').removeClass('has-error');
    }
}

/**
 * Change view and module when package change.
 *
 * @param  int    $packageID
 * @access public
 * @return void
 */
function changeViewAndModule(packageID)
{
    if(packageID == '') return false;
    var moduleName = packageModulePairs[packageID];
    if($('#module').val() != moduleName) $('#module').data('zui.picker').setValue(moduleName);
    if($('#view').val() != moduleViewPairs[moduleName]) $('#view').val(moduleViewPairs[moduleName]).trigger('chosen:updated');
    $('#module').closest('td').find('.text-danger.help-text').remove();
    $('#module').closest('td').find('.has-error').removeClass('has-error');
    $('#view').closest('td').find('.text-danger.help-text').remove();
    $('#view').closest('td').find('.has-error').removeClass('has-error');
}
