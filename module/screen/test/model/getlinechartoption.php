#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->getLineChartOption()。
timeout=0
cid=1

- 测试获取折线图配置中dimensions是否正确，值为年份。 @1
- 测试获取折线图配置中source是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';
su('admin');

zenData('project')->gen(50);
zenData('story')->gen(20);
zenData('bug')->gen(20);

$screen = new screenTest();

function getComponetAndChart($screen, $filters = array())
{
    global $tester;
    $componets = $screen->getAllComponent($filters);
    foreach($componets as $componet)
    {
        if(!isset($componet->sourceID)) continue;
        $type  = $componet->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $tester->dao->select('*')->from($table)->where('id')->eq($componet->sourceID)->fetch();
        $componet->option->dataset = new stdclass();
        if($chart) return array($componet, $chart);
    }
    return array(null, null);
}

$filter1  = array('type' => 'line');

list($component1, $chart1) = getComponetAndChart($screen, $filter1);
$screen->getLineChartOption($component1, $chart1);
$dimension_0 = $component1->option->dataset->dimensions[0] ?? null;
$dimension_1 = $component1->option->dataset->dimensions[1] ?? null;
$year = date('Y', strtotime('-1 month'));
$source_0 = $component1->option->dataset->source[0];
r($dimension_0 && $dimension_0 == 'year') && p('') && e('1');                                                                                       //测试获取折线图配置中dimensions是否正确，值为年份。
r($dimension_0 && $dimension_1 && $source_0 && $source_0->{$dimension_0} == $year && $source_0->{$dimension_1} === '1.0000') && p('') && e('1');    //测试获取折线图配置中source是否正确。