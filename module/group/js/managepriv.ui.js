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
