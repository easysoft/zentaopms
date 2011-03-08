function check(checker, module)
{
    $('#' + module + ' input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

/**
 * Control the actions select control for a module.
 * 
 * @param   string $module 
 * @access  public
 * @return  void
 */
function setModuleActions(module)
{
    $('#actionBox select').addClass('hidden');          // Hide all select first.
    $('#actionBox select').val('');                     // Unselect all select.
    $('.' + module + 'Actions').removeClass('hidden');  // Show the action control for current module.
}
