#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';
su('admin');

zdTable('project')->gen(50);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);

/**

title=测试 screenModel->getTableChartOption();
timeout=0
cid=1

- 测试type为table的图表是否显示正确，生成的header指标项数量是否正确。 @1
- 测试type为table的图表是否显示正确，生成的dataset数据项数量是否正确。 @1

*/

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
        if($chart) return array($componet, $chart);
    }
    return array(null, null);
}

$filter9  = array('type' => 'table');

list($component9, $chart9) = getComponetAndChart($screen, $filter9);
$screen->getChartOptionTest($chart9, $component9);
$option = $component9->option;
r(isset($option->header[0])  && count($option->header[0]) == 10) && p('') && e(1);   //测试type为table的图表是否显示正确，生成的header指标项数量是否正确。
r(isset($option->dataset[0]) && count($option->dataset[0]) == 10) && p('') && e(1);  //测试type为table的图表是否显示正确，生成的dataset数据项数量是否正确。
