window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'realname' && row.data.jira)
    {
        result[0] = {html: '<div class="flex items-center"><img src="static/png/jirauser.png" class="mr-1"></img>' + result[0] + '</div>'};
    }

    return result;
}
