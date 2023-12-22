#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';
su('admin');

zdTable('project')->gen(50);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);

/**

title=测试 screenModel->getchartoption();
timeout=0
cid=1

- 测试type为pie的图表是否显示正确，生成的指标项和数据项是否正确。 @0
- 测试type为pie的图表是否显示正确，生成的指标项和数据项是否正确。 @1

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

$filter8  = array('type' => 'pie');

list($component8, $chart8) = getComponetAndChart($screen, $filter8);
$screen->getChartOptionTest($chart8, $component8);
$dataset = $component8->option->dataset ?? null;
$serires = $component8->option->series ?? null;
r($dataset->dimensions[0] == 'completeStatus' && $dataset->source[0]->completeStatus == '延期完成项目') && p('') && e(1);               //测试type为pie的图表是否显示正确，生成的指标项是否正确和数据项是否正确。。

$check = true;
$pieConfig = $serires[0];
if($pieConfig->type != 'pie') $check = false;
if($pieConfig->radius != '70%') $check = false;
if($pieConfig->center[0] != '50%') $check = false;
if($pieConfig->center[1] != '60%') $check = false;
r($check) && p('') && e(1);               //测试type为pie的图表是否显示正确，生成的数据项是否正确。
