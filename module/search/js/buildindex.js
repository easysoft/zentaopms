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
                className  = response.type + 'count';
                $typeCount = $('#resultBox .' + className)
                if($typeCount.length == 0)
                {
                    $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                }
                else
                {
                    count = parseInt($typeCount.html()) + parseInt(response.count);
                    $typeCount.html(count);
                }

                $('#execButton').attr('href', response.next);
                return $('#execButton').click();
            }
        });
        return false;
    });
})
