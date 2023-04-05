function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=byGroup&param="+ groupID + "&menu=&version=" + value);
}

/**
 * Control the packages select control for a module.
 *
 * @param   string $module
 * @access  public
 * @return  void
 */
function setModulePackages(module)
{
    $('#packageBox select').addClass('hidden');                      // Hide all select first.
    $('#packageBox select').val('');                                 // Unselect all select.
    $("select[data-module='" + module + "']").removeClass('hidden'); // Show the action control for current module.

    updatePrivList('module', module);
}

/**
 * Control the actions select control for a package.
 *
 * @access public
 * @return void
 */
function setActions()
{
    $('#actionBox select').val('');

    var hasSelectedPackage = $('#packageBox select').not('.hidden').val().join(',');

    updatePrivList('package', hasSelectedPackage);

}

function setNoChecked()
{
    var noCheckValue = '';
    $(':checkbox').each(function()
    {
        if(!$(this).prop('checked') && $(this).next('span').attr('id') != undefined) noCheckValue = noCheckValue + ',' + $(this).next('span').attr('id');
    })
    $('#noChecked').val(noCheckValue);
}

/**
 * Update the action box when module or package selected.
 *
 * @param  string  parentType  module|package
 * @param  string  parentList
 * @access public
 * @return void
 */
function updatePrivList(parentType, parentList)
{
    $.get(createLink('group', 'ajaxGetPrivByParents', 'parentType=' + parentType + '&parentList=' + parentList), function(data)
    {
        $('#actions').replaceWith(data);
    })
}

/**
 * Change parent item checked.
 *
 * @access public
 * @return void
 */
function changeParentChecked($item, moduleName, packageID)
{
    var moduleAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').length;
    var moduleSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').find('[checked=checked]').length;
    var $moduleItem       = $item.closest('tbody').find('.module[data-module=' + moduleName +']');
    if(moduleSelectPrivs == 0)
    {
      $moduleItem.find('input[type=checkbox]').removeAttr('checked');
      $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(moduleAllPrivs == moduleSelectPrivs)
    {
      $moduleItem.find('input[type=checkbox]').attr('checked', 'checked');
      $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
      $moduleItem.find('input[type=checkbox]').removeAttr('checked');
      $moduleItem.find('label').addClass('checkbox-indeterminate-block');
    }

    if(packageID == '') return;

    var packageAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').length;
    var packageSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').find('[checked=checked]').length;
    var $packageItem       = $item.closest('tbody').find('.package[data-module=' + moduleName +'][data-package=' + packageID +']');
    if(packageSelectPrivs == 0)
    {
      $packageItem.find('input[type=checkbox]').removeAttr('checked');
      $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(packageAllPrivs == packageSelectPrivs)
    {
      $packageItem.find('input[type=checkbox]').attr('checked', 'checked');
      $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
      $packageItem.find('input[type=checkbox]').removeAttr('checked');
      $packageItem.find('label').addClass('checkbox-indeterminate-block');
    }
}

$(function()
{
    $('#privList > tbody > tr > th .check-all').change(function()
    {
        var id      = $(this).find('input[type=checkbox]').attr('id');
        var checked = $(this).find('input[type=checkbox]').prop('checked');

        if(id == 'allChecker')
        {
            $('input[type=checkbox]').prop('checked', checked);

            if(checked) $('input[type=checkbox]').attr('checked', checked);
            if(!checked) $('input[type=checkbox]').removeAttr('checked');
            $(this).closest('tbody').find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
        }
        else
        {
            var moduleName = $(this).closest('th').attr('data-module');
            var packageID  = $(this).closest('th').hasClass('package') ? $(this).closest('th').attr('data-package') : '';
            var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-module=' + moduleName +'][data-package=' + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

            $children.find('input[type=checkbox]').prop('checked', checked);
            $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

            if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
            if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

            changeParentChecked($(this), moduleName, packageID);
        }
    });

    $('#privList > tbody > tr > td input[type=checkbox]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $(this).attr('checked', 'checked');
        }
        else
        {
            $(this).removeAttr('checked');
        }
        var moduleName        = $(this).closest('.group-item').attr('data-module');
        var packageID          = $(this).closest('.group-item').attr('data-package');
        changeParentChecked($(this), moduleName, packageID);
    });
})
