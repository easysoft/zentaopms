$(document).ready(function()
{
    $('#tagSearch').click(function()
    {
        triggerSearch();
    });
    $('#keyword').keypress(function(event)
    {
        if(event.which == 13) triggerSearch();
    })

});

function triggerSearch()
{
    $("#tagForm").submit();
}
