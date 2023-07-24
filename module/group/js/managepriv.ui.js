$(function()
{
    positionBtn();

    $(document).on('change', '#privList > tbody > tr > th .check-all', checkAllChange);
    $(document).on('change', '#privPackageList > tbody > tr .check-all', checkAllChange);
})

/**
 * When check all change.
 *
 * @access public
 * @return void
 */
function checkAllChange()
{
    var id      = $(this).find('input[type=checkbox]').attr('id');
    var checked = $(this).find('input[type=checkbox]').prop('checked');

    if(id == 'allChecker')
    {
        $('input[type=checkbox]').prop('checked', checked);

        if(checked) $('input[type=checkbox]').attr('checked', checked);
        if(!checked) $('input[type=checkbox]').removeAttr('checked');
        $('tbody .checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
    }
    else if($(this).closest('#privList').length > 0)
    {
        var moduleName = $(this).closest('th').attr('data-module');
        var packageID  = $(this).closest('th').hasClass('package') ? $(this).closest('th').attr('data-package') : '';
        var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-divID=' + moduleName + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

 //       changeParentChecked($(this), moduleName, packageID);
    }
    else if($(this).closest('#privPackageList').length > 0)
    {
        var moduleName = $(this).parent().attr('data-module');
        var packageID  = $(this).parent().hasClass('package') ? $(this).parent().attr('data-package') : '';
        var $children  = $(this).parent().hasClass('package') ? $(this).closest('td').find('[data-divID=' + moduleName + packageID + ']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

//        changeParentChecked($(this), moduleName, packageID);
    }
//    updatePrivTree(null);
}

window.onsize = function()
{
    positionBtn();
}


/**
 * Change btn position.
 *
 * @access public
 * @return void
 */
function positionBtn()
{
    let btnLeft = $('.main.main-content > table').width() - 75;
    $('.main.main-content > .btn-group').css('left', btnLeft).css('visibility', 'unset');
}

/**
 * Control the packages select control for a module.
 *
 * @access  public
 * @return  void
 */
window.setModulePackages = function()
{
    const module = $(this).val();
    $('#packageBox select').addClass('hidden');                      // Hide all select first.
    $('#packageBox select').val('');                                 // Unselect all select.
    $("select[data-module='" + module + "']").removeClass('hidden'); // Show the action control for current module.

    updatePrivList('module', module);
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
    const selectedModule = $('#module').val();
    $.get($.createLink('group', 'ajaxGetPrivByParents', 'module=' + selectedModule + '&parentType=' + parentType + '&parentList=' + parentList), function(data)
    {
        $('#actions').replaceWith(data);
    })
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

    var hasSelectedPackage = ',' + $('#packageBox select').not('.hidden').val().join(',') + ',';

    updatePrivList('package', hasSelectedPackage);

}
