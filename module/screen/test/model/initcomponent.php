#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

/**
title=测试 screenModel->initComponent();
cid=1
pid=1

当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表类型为组件的设置类型。   >> xline
当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表id为组件的设置id。       >> 1000
当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表名称为组件的设置名称。   >> test chart
当图表为自定义图表时, 判断是否正确生成了option对象。                                     >> 1
当图表为自定义图表时，判断是否正确生成了option->dataset对象。                            >>1
当图表为自定义图表时，组件的设置名称为图表的名称。                                       >> test chart
当图表为自定义图表时，组件的设置id为图表的id。                                           >> 1
*/

$screen = new screenTest();

$component1 = new stdclass();
$component1->option = new stdclass();
$component1->chartConfig = new stdclass();
$component1->chartConfig->key = 'Select1';
$component1->key = 'Select';
$chart1 = new stdclass();
$chart1->id       = 1000;
$chart1->name     = 'test chart';
$chart1->builtin  = true;
$chart1->settings = json_encode(array('x' => 1, 'y' => 2));
$chart1->type     = 'xline';

$component2 = new stdclass();
$component2->chartConfig = new stdclass();
$component2->chartConfig->key = 'BarCommon';
$chart2 = new stdclass();
$chart2->id       = 1;
$chart2->name     = 'test chart';
$chart2->builtin  = false;
$chart2->type     = 'line';
$chart2->settings = json_encode(array(array('type' => 'cluBarX', 'key' => 'xxx')));

$componentList = array($component1, $component2);
$typeList      = array('chart', 'pivot');
$chartList     = array($chart1, $chart2);

$screen->initComponentTest($chartList[0], $typeList[0], $componentList[0]);
r($componentList[0]->type) && p('') && e('xline');                      //当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表类型为组件的设置类型。
r($componentList[0]->id)   && p('') && e(1000);                         //当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表id为组件的设置id。
r($componentList[0]->title) && p('') && e('test chart');                //当图表为内置图表并且图表id不在系统设置的内置图表id列表中时，图表名称为组件的设置名称。

$screen->initComponentTest($chartList[1], $typeList[0], $componentList[1]);
r(isset($componentList[1]->option)) && p('') && e(1);                  //当图表为自定义图表时, 判断是否正确生成了option对象。
r(isset($componentList[1]->option->dataset)) && p('') && e(1);         //当图表为自定义图表时，判断是否正确生成了option->dataset对象。
r($componentList[1]->chartConfig->title) && p('') && e('test chart');  //当图表为自定义图表时，组件的设置名称为图表的名称。
r($componentList[1]->chartConfig->sourceID)    && p('') && e(1);       //当图表为自定义图表时，组件的设置id为图表的id。
