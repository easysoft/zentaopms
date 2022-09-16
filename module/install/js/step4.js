$(function()
{
    $("#useLean").click(function()
    {
        $('#mode').val('lean');
        $('form').submit();
    })

    $("#useNew").click(function()
    {
        $('#mode').val('new');
        $('form').submit();
    })
});
