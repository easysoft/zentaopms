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
    $('[name^=https]').change(function()
    {   
        var value = $(this).val();
        $('#isHttps').val(value);
        if(value == 'on')
        {   
            $('.sslTR').show();
        }   
        else
        {   
            $('.sslTR').hide();
        }   
    }); 

    $('#os').change(function()
    {   
        os = $(this).val();
        $('.download-package').attr('href', createLink('setting', 'downloadxxd', "type=package&os=" + os));
    }); 
    $('#os').change();
});
