/**
 * Create key for an entry.
 *
 * @access public
 * @return void
 */
function createKey()
{
    var chars = '0123456789abcdefghiklmnopqrstuvwxyz'.split('');
    var key   = '';
    for(var i = 0; i < 32; i ++)
    {
        key += chars[Math.floor(Math.random() * chars.length)];
    }
    $('#key').val(key);
}

/**
 * Disable ip if allIP is checked.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleAllIP(event)
{
    if($(event.target).prop('checked'))
    {
        $('#ip').attr('disabled', 'disabled');
        $('#ip').after("<input class='form-group hidden' type='text' id='ip' name='ip'>");
    }
    else
    {
        $('#ip').removeAttr('disabled');
        $('input.hidden#ip').remove();
    }
}

/**
 * Hide account row when enabling password-free login.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleFreePasswd(event)
{
    if($(event.target).val() == '1')
    {
      $('#account').closest('.form-row').addClass('hidden');
    }
    else
    {
      $('#account').closest('.form-row').removeClass('hidden');
    }
}
