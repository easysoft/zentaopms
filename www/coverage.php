<?php
include_once(dirname(__FILE__, 2) . "/test/lib/coverage.php");
global $zentaoRoot;
$zentaoRoot = dirname(__FILE__, 2);

$type     = isset($_GET['module']) ? 'module' : 'summary';
$coverage = new coverage();
$report   = '';

switch($type)
{
    case 'summary':
        $report = $coverage->genSummaryReport();
        break;
    case 'module':
        $module = $_GET['module'];
        $file   = $_GET['file'];
        $report = $coverage->genSummaryReport($module, $file);
        break;
    default:
        $report = $coverage->genSummaryReport();
        break;
}
echo $report;
echo "<script>var type = '$type';</script>";
?>
<script src="./js/jquery/lib.js"></script>
<script>
$().ready(function()
{
    if(type == 'summary')
    {
        renderColorByCoveragePercent()
        implementExpand();
        implementSort();
    }
});

function implementSort()
{
    var table = $('#summaryTable');
    var tbody = table.find('tbody');
    var rowsArr = tbody.find('tr').toArray();

    rowsArr.sort(function(row1, row2)
    {
        /* Get seventh row and translate it's value into int. */
        var val1 = $(row1).find('th:eq(0)').text();
        var val2 = $(row2).find('th:eq(0)').text();

        if (val1 < val2)
        {
            return -1;
        }
        else if (val1 > val2)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    });

    $.each(rowsArr, function(index, row)
    {
        tbody.append(row);
    });
}

function renderColorByCoveragePercent()
{
    $('table tbody tr td').each(function()
    {
        var text = $(this).text();
        if(text.indexOf('%') > -1)
        {
            var percent = parseInt(text);
            if(percent < 50)
            {
                $(this).css('color', 'red');
            }
            else if(percent < 80)
            {
                $(this).css('color', 'orange');
            }
            else
            {
                $(this).css('color', 'green');
            }
        }
    });
}

function implementExpand()
{
    $("tr[name$='-child']").hide();
    $("tr[name$='-parent']").click(function()
    {
        $(this).nextUntil("tr[name$='-parent']").slideToggle('fast');
    });
}
</script>
