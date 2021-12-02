$(document).ready(function()
{
    $('#projectSearch').click(function()
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
    $("#gitlabprojectForm").submit();
}
