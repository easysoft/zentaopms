$(document).ready(function()
{
    $('#branchSearch').click(function()
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
    $("#branchPrivForm").submit();
}
