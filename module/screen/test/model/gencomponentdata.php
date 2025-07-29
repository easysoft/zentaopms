#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->buildChart();
timeout=0
cid=1

- 判断图表的sql字段是否被修改 @1
- 判断图表的fields字段是否被修改 @1
- 判断图表的settings字段是否被修改 @1
- 判断图表的sql字段是否被修改 @0
- 判断图表的fields字段是否被修改 @1
- 判断图表的settings字段是否被修改 @1

*/

zenData('screen')->gen(0);

global $tester;
$screen = new screenTest();

$components = $screen->getAllComponent(array(), true);

foreach($components as $component)
{
    if(isset($component->key) && $component->key == 'chart') continue;
    $chartID = zget($component->chartConfig, 'sourceID', '');
    if(!$chartID) continue;
    if(isset($component1) && isset($component2)) break;

    if($component->chartConfig->package == 'Tables')
    {
        $component1 = $component;
        $type1  = 'pivot';
        $chart1 = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($chartID)->fetch();
    }
    else
    {
        $component2 = $component;
        $type2 = 'chart';
        $chart2 = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq($chartID)->fetch();
    }
}

$componentList = array($component1, $component2);
$typeList = array($type1, $type2);
$chartList = array($chart1, $chart2);

$chart1->sql .= ";;;;;";
$chart1_ = clone($chart1);
$screen->genComponentDataTest($chartList[0], $componentList[0], $typeList[0], array());
r($chart1->sql === $chart1_->sql)           && p('') && e(1);  //判断图表的sql字段是否被修改
r($chart1->fields === $chart1_->fields)     && p('') && e(1);  //判断图表的fields字段是否被修改
r($chart1->settings === $chart1_->settings) && p('') && e(1);  //判断图表的settings字段是否被修改

$chart2_ = clone($chart2);
$chart2->sql = '';
$chart2->filters = '[{"field":"type","type":"int"}]';
$screen->genComponentDataTest($chartList[1], $componentList[1], $typeList[1], array());

r($chart2->sql === $chart2_->sql)           && p('') && e(0);  //判断图表的sql字段是否被修改
r($chart2->fields === $chart2_->fields)     && p('') && e(1);  //判断图表的fields字段是否被修改
r($chart2->settings === $chart2_->settings) && p('') && e(1);  //判断图表的settings字段是否被修改
