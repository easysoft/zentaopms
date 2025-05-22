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
