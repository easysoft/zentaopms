window.injectWithXXC = function(message)
{
    $('#title').val(message.content);
    if(message.type == 'url')
    {
        $('#typeurl').attr('checked', 'checked');
        $('input[name="type"]').change();
    }
}
