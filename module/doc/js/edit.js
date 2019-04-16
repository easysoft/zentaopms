$(function()
{
    $('#top-submit').click(function()
    {
        $(this).addClass('disabled');
        $('form').submit();
    })
    toggleAcl($('input[name="acl"]:checked').val(), 'doc');
    $('input[name="type"]').change(function()
    {
        var type = $(this).val();
        if(type == 'text')
        {
            $('#contentBox').removeClass('hidden');
            $('#urlBox').addClass('hidden');
        }
        else if(type == 'url')
        {
            $('#contentBox').addClass('hidden');
            $('#urlBox').removeClass('hidden');
        }
    });
})
