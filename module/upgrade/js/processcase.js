$(document).ready(function()
{
    $('#execButton').click(function()
    {
        $('#execButton').attr('disabled', true);

        $.getJSON($(this).attr('href'), function(response)
        {
            if(response.result == 'finish')
            {
                $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                $('#nextButton').removeClass('hidden');
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
