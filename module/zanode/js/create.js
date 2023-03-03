/**
 * Change zanode type.
 *
 * @access public
 * @return void
 */
function zanodeType()
{
    if($('#type').val() == 'physics')
    {
        $('#parent').closest('tr').hide();
        $('#image').closest('tr').hide();
        $('#extranet').closest('tr').removeClass('hidden');
        $('#osName').addClass('hidden');
        $('#osNamePhysics').removeClass('hidden');
    }
    else
    {
        $('#parent').closest('tr').show();
        $('#image').closest('tr').show();
        $('#extranet').closest('tr').addClass('hidden');
        $('#osName').removeClass('hidden');
        $('#osNamePhysics').addClass('hidden');
    }
}

/**
 * Load hosts.
 *
 * @access public
 * @return void
 */
function loadHosts()
{
    var hostLink = createLink('zahost', 'ajaxGetHosts');
    $('#hostIdBox').load(hostLink, function()
    {
        $('#hostIdBox').find('#parent').chosen();
    });
}
