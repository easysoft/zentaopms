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

/**
 * Trigger filtering function.
 *
 * @access public
 * @return void
 */
function triggerSearch()
{
    $("#sonarqubeProjectForm").submit();
}
