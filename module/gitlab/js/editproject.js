$(function()
{
    $('input:radio[name="visibility"]').change(function()
    {
        var visibility = $('input:radio[name="visibility"]:checked').val();
        if(visibility == 'public') $('#publicTip').removeClass('hidden');
        if(visibility != 'public') $('#publicTip').addClass('hidden');
    })
})
