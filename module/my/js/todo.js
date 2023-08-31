function changeDate(date)
{
    date = date.replace(/\-/g, '');
    link = createLink('my', 'todo', 'type=' + date);
    location.href=link;
}

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#todoForm [name^=todoIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};

$(function()
{
    // Support hash start with todo-1
    var hash = window.location.hash.toLowerCase();
    if(hash.indexOf('#todo-') === 0)
    {
        var todoId = hash.substr(6);
        var url = createLink('todo', 'view', 'id=' + todoId + '&from=my', '', 'yes');
        var $a = $('<a/>');
        $a.attr({href: url}).modalTrigger(
        {
            'data-toggle': 'modal',
            type: 'iframe'
        }).trigger('click');
    }

    $('#todoForm').table(
    {
        replaceId: 'todoIDList',
        statisticCreator: function(table)
        {
            var $table            = table.getTable();
            var $checkedRows      = $table.find('tbody>tr.checked');
            var checkedTotal      = $checkedRows.length;
            var checkedWait       = $checkedRows.filter("[data-status=wait]").length;
            var checkedDoing      = $checkedRows.filter("[data-status=doing]").length;
            var checkedStatistics = checkedSummary.replace('%total%', checkedTotal)
                    .replace('%wait%', checkedWait)
                    .replace('%doing%', checkedDoing);

            return checkedTotal ? checkedStatistics : pageSummary;
        }
    });
});
