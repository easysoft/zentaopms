#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->getLineChartOption()。
timeout=0
cid=1

- 测试chartKey和标题。
 - 属性chartKey @VLineCommon
 - 属性title @有效Bug率年度趋势图
- 测试option标题和显示。
 - 属性text @有效Bug率年度趋势图
- 测试正确生成了dimensions。 @1
- 测试正确生成了source。 @1

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
        if($chart) return array($componet, $chart);
    }
    return array(null, null);
}

$filter1  = array('type' => 'line');

list($component1, $chart1) = getComponetAndChart($screen, $filter1);
$screen->getLineChartOption($component1, $chart1);

r($component1->chartConfig)                           && p('chartKey,title') && e('VLineCommon,有效Bug率年度趋势图'); // 测试chartKey和标题。
r($component1->option->title)                         && p('text')           && e('有效Bug率年度趋势图');             // 测试option标题。
r(is_array($component1->option->dataset->dimensions)) && p('')               && e(1);                                // 测试正确生成了dimensions。
r(is_array($component1->option->dataset->source))     && p('')               && e(1);                                // 测试正确生成了source。