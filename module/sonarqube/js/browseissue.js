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

    $('.pager a').each(function()
    {
        $(this).attr('data-url', $(this).attr('href'));
        $(this).attr('href', '###');
    });

    $('.pager a').click(function()
    {
        $("#sonarqubeIssueForm").attr('action', $(this).data('url'))
        triggerSearch();
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
