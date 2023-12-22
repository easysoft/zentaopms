#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildChart();
timeout=0
cid=1

- 判断所有的组件以及内部的图表是否都被处理了。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screenTest = new screenTest();

$screenID = 6;

$screen = $tester->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();

$oriScheme = json_decode($screen->scheme);

$componentList = array_map(function($component){return clone($component);}, $oriScheme->componentList);

$handleScheme = $screenTest->genNewChartData($screen);

$fComponentList = $handleScheme->componentList;

$check = false;
foreach($componentList as $key => $component) $check = serialize($component) == serialize($fComponentList[$key]);
r($check) && p('') && e(0);  //判断所有的组件以及内部的图表是否都被处理了。
