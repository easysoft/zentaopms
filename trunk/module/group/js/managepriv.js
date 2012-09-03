function showPriv(value)
{
    $('.priv').removeClass('red');
    privs = newPriv[value];
    for(var item in privs)
    {
        $('#' + privs[item]).addClass('red');
    }
}

/* Override the setHelpLink(). */
function setHelpLink(){}

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

$(function()
{
    showPriv(version);
}
);
