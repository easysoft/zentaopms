$(function()
{
    if($('tr').length == 0) return false;

    if($('tr').first().data('status') == 'ready')
    {
        $('tr').first().trigger('click');
    }
});
