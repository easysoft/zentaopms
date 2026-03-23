window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'realname' && row.data.jira)
    {
        result[0] = {html: '<icon class="icon icon-link mr-1"></icon>' + result[0]};
    }

    return result;
}
