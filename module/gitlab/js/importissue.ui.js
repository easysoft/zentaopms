window.renderRowCol = function(result, col, row)
{
    if(col.name == 'title_static')
    {
        $(result[0]).html('<a href="' + row.web_url + '" target="_blank">' + row.title + '</a>');
    }

    return result;
}

function loadProductExecutions(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const host        = $target.val();
    const projects    = $currentRow.find('div.picker-box[data-name="executionList"]');

    $.get($.createLink('gitlab', 'ajaxGetExecutionsByProduct', 'host=' + host), function(response)
    {
        var items = JSON.parse(response);
        $(projects).zui('picker').render({items: items});
    });
}
