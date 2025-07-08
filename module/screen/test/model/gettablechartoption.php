#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';
su('admin');

zenData('project')->gen(50);
zenData('story')->gen(20);
zenData('bug')->gen(20);

/**

title=测试 screenModel->getTableChartOption();
timeout=0
cid=1

- 测试组件类型。 @TableMergeCell
- 测试type为table的图表是否显示正确。 @1
- 测试type为table的图表是否显示正确。 @1
- 测试生成的header指标项数量是否正确。 @10
- 测试生成的dataset数据项数量是否正确。 @10

*/

zenData('screen')->gen(0);

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

r($component9->key) && p('') && e('TableMergeCell'); // 测试组件类型。

r(isset($option->header[0]))  && p('') && e(1);  // 测试type为table的图表是否显示正确。
r(isset($option->dataset[0])) && p('') && e(1);  // 测试type为table的图表是否显示正确。
r(count($option->header[0]))  && p('') && e(10); // 测试生成的header指标项数量是否正确。
r(count($option->dataset[0])) && p('') && e(10); // 测试生成的dataset数据项数量是否正确。
