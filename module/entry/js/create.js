/**
 * create key for an entry.
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

    return false;
}

$(function()
{
    $('#allIP').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#ip').attr('disabled', 'disabled');
        }
        else
        {
            $('#ip').removeAttr('disabled');
        }
    })
    
    $('#name').focus();

    $("input[id^=freePasswd]").change(function()
    {
        if($(this).val() == 1) 
        {
            $('#account').closest('tr').addClass('hidden'); 
        }
        else
        {
            $('#account').closest('tr').removeClass('hidden'); 
        }
    })
});
