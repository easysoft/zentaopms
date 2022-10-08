$(document).ready(function()
{
    $('#issueSearch').click(function()
    {
        triggerSearch();
    });

    $('#keyword').keypress(function(event)
    {
        if(event.which == 13) triggerSearch();
    });

    $('.c-actions>a').click(function()
    {
        issueTitle = $(this).parent().parent().children(':first').attr('title');
        $.cookie('sonarqubeIssue', issueTitle);
    });
});

/**
 * Trigger filtering function.
 *
 * @access public
 * @return void
 */
function triggerSearch()
{
    $("#sonarqubeIssueForm").submit();
}
