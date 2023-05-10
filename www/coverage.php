<?php
include_once(dirname(__FILE__, 2) . "/test/lib/coverage.php");
global $zentaoRoot;
$zentaoRoot = dirname(__FILE__, 2);

$type      = isset($_GET['module']) ? 'module' : 'summary';
$coverage  = new coverage();
$report    = '';
$ztfReport = $coverage->getZtfReport();
if($ztfReport)
{
    $ztfHtml = "<div class='report'><strong>%s</strong> 执行 <strong>%s个</strong>用例，耗时 <strong>%s秒</strong>。<strong>%s (%s%%) </strong>通过，<strong>%s (%s%%)</strong> 失败，<strong>%s (%s%%)</strong> 忽略。</div>";
    $ztfHtml = sprintf($ztfHtml, $ztfReport->time, $ztfReport->total, $ztfReport->duration, $ztfReport->pass, $ztfReport->passPercent, $ztfReport->fail, $ztfReport->failPercent, $ztfReport->skip, $ztfReport->skipPercent);
}
else
{
    $ztfPath = $coverage->loadTraceFromFile('ztfPath');
    $ztfHtml = "<p>在{$ztfPath}/ 没有找到ZTF测试报告。</p>";
}


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
?>
<!DOCTYPE html>
<html lang="zh-cn" xml:lang="zh-cn">
<head>
  <meta charset="UTF-8">
  <title>单元测试行覆盖率报告</title>
</head>
<style>
body {font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5; margin: 0; padding: 20px; }
table {border-collapse: collapse; max-width: 100%; width: 100%; margin: 20px 0; }
th {border: 1px solid #ccc; padding: 8px; text-align: center; background-color: #eee; white-space: nowrap; }
caption{font-weight: bold; margin: 10px 0; font-size: 18px; }
h2 {margin-top: 20px; font-size: 24px; }
.red {color: red; }
.green {color: green; }
tbody tr:hover {background-color: #f5f5f5; }
tbody tr:nth-child(even) {background-color: #f9f9f9; }
h1 {text-align: center; }
</style>
<body>
<h1>单元测试行覆盖率报告</h1>
<?php
echo $ztfHtml;
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

