window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'realname' && row.data.jira)
    {
        result[0] = {html: '<div class="flex items-center"><icon class="icon icon-jira mr-1"></icon>' + result[0] + '</div>'};
    }

    return result;
}
