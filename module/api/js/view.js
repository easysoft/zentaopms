$(document).ready(function()
{
    var options = 
    {
        target : null,
        timeout : 30000,
        dataType: 'json',
        success:function(response,statusText)
        {
            $('#result .url').html(response.url);
            $('#result .status').html(response.status);
            $('#result .data').html(response.data);
            $('#result').removeClass('hidden');
        }
    };
    $('#apiForm').submit(function()
    {
        $(this).ajaxSubmit(options);
        return false;
    });
});
