$(function()
{
    $('#testtaskForm').table(
    {
        replaceId: 'taskIdList',
        statisticCreator: function(table)
        {
            var $table            = table.getTable();
            var $checkedRows      = $table.find('tbody>tr.checked');
            var checkedTotal      = $checkedRows.length;
            var checkedWait       = $checkedRows.filter("[data-status=wait]").length;
            var checkedTesting    = $checkedRows.filter("[data-status=doing]").length;
            var checkedBlocked    = $checkedRows.filter("[data-status=blocked]").length;
            var checkedDone       = $checkedRows.filter("[data-status=done]").length;
            var checkedStatistics = checkedAllSummary.replace('%total%', checkedTotal)
                    .replace('%wait%', checkedWait)
                    .replace('%testing%', checkedTesting)
                    .replace('%blocked%', checkedBlocked)
                    .replace('%done%', checkedDone);

            return checkedTotal ? checkedStatistics : pageSummary;
        }
    });
});
