$('#linkCaseForm').table(
{
    statisticCreator: function(table)
    {
        var $checkedRows = table.getTable().find('tbody>tr.checked');
        var checkedTotal = $checkedRows.length;
        if(!checkedTotal) return;

        return selectedItems.replace('{0}', checkedTotal);
    }
})
