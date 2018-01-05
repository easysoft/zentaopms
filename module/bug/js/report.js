$(function()
{
    var resizeChartTable = function()
    {
        $('.table-wrapper').each(function()
        {
            var $this = $(this);
            $this.css('max-height', $this.closest('.table').find('.chart-wrapper').outerHeight());
        });
    };
    resizeChartTable();
    fixedTableHead('.table-wrapper');
    $(window).resize(resizeChartTable);
});

function changeChartType(type)
{
    $('form').attr('action', createLink('bug', 'report', 'productID=' + productID + '&browseType=' + browseType + '&branchID=' + branchID + '&moduleID=' + moduleID + '&chartType=' + type));
    $('form').find('#submit').click();
}
