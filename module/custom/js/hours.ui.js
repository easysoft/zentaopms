$(document).on('change', '[name=weekend]', function(e)
{
    $('#restDayBox').toggleClass('hidden', $(e.target).val() != 1)
})
