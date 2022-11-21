$(function()
{
    $("#useLight").click(function()
    {
        $('#mode').val('light');
        $('form').submit();
    })

    $("#useALM").click(function()
    {
        $('#mode').val('ALM');
        $('form').submit();
    })
});
