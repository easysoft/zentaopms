<?php
include_once(dirname(__FILE__, 2) . "/test/lib/coverage.php");
global $zentaoRoot;
$zentaoRoot = dirname(__FILE__, 2);

$type      = isset($_GET['module']) ? 'module' : 'summary';
$coverage  = new coverage();
$report    = '';
$ztfReport = $coverage->getZtfReport('web');
if($ztfReport)
{
    $ztfHtml = "<div class='report'><strong>%s</strong> 执行 <strong>%s个</strong>用例，耗时 <strong>%s秒</strong>。<strong>%s (%s%%) </strong>通过，<strong>%s (%s%%)</strong> 失败，<strong>%s (%s%%)</strong> 忽略。</div>";
    $ztfHtml = sprintf($ztfHtml, $ztfReport->time, $ztfReport->total, $ztfReport->duration, $ztfReport->pass, $ztfReport->passPercent, $ztfReport->fail, $ztfReport->failPercent, $ztfReport->skip, $ztfReport->skipPercent);
}
else
{
    $ztfHtml = "<p>没有找到ZTF测试报告。</p>";
}

switch($type)
{
    case 'summary':
        $report = $coverage->genWebSummaryReport();
        break;
    case 'module':
        $module = $_GET['module'];
        $file   = $_GET['file'];
        $report = $coverage->genWebSummaryReport($module, $file);
        break;
    default:
        $report = $coverage->genWebSummaryReport();
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
