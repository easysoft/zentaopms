/**
 * Change zanode type.
 *
 * @access public
 * @return void
 */
function zanodeType()
{
    if($('#hostType').val() == 'physics')
    {
        $('#parent').closest('tr').hide();
        $('#image').closest('tr').hide();
        $('#extranet').closest('tr').removeClass('hidden');
        $('#osName').addClass('hidden');
        $('#osNamePhysicsContainer').removeClass('hidden');
    }
    else
    {
        $('#parent').closest('tr').show();
        $('#image').closest('tr').show();
        $('#extranet').closest('tr').addClass('hidden');
        $('#osName').removeClass('hidden');
        $('#osNamePhysicsContainer').addClass('hidden');
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

$(function()
{
    $('#osNamePhysicsPre').on('change', function()
    {
        if($(this).val() == 'linux')
        {
            $('#osNamePhysics').empty();
            for(var i in linuxList)
            {
                $('#osNamePhysics').append('<option value="' + i + '">' + linuxList[i] + '</option>')
            }
        }
        else
        {
            $('#osNamePhysics').empty();
            for(var i in windowsList)
            {
                $('#osNamePhysics').append('<option value="' + i + '">' + windowsList[i] + '</option>')
            }
        }
        $('#osNamePhysics').trigger('chosen:updated');
    })
})