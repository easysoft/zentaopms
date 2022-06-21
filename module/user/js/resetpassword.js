$(function()
{
    if(expired)
    {
        setInterval(function()
        {
            var time = $('#time').text();
            if(time == 0) window.location.href = $('.alert .btn').attr('href');
            $('#time').text(time-1 <=0 ? 0 : time -1);
        }, 1000);
    }
})
