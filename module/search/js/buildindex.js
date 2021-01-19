$(document).ready(function()
{
    $('#execButton').click(function()
    {
        $('#execButton').hide();

        $.getJSON($(this).attr('href'), function(response)
        {
            if(response.result == 'finished')
            {
                $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                return false;
            }
            else
            {
                $('#execButton').attr('href', response.next);
                $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                return $('#execButton').click();
            }
        });
        return false;
    });
})
