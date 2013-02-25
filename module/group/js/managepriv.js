function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=byGroup&param="+ groupID + "&menu=&version=" + value);
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

function setNoChecked()
{
    var noCheckValue = '';
    $(':checkbox').each(function(){
        if(!$(this).attr('checked') && $(this).next('span').attr('id') != undefined) noCheckValue = noCheckValue + ',' + $(this).next('span').attr('id');
    })
    $('#noChecked').val(noCheckValue);
}
