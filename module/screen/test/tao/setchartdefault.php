#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

/**
title=测试 screenModel->buildChart();
cid=1
pid=1

当type为line的时候，判断是否生成了默认属性       >> 1
当type为table的时候，判断是否生成了默认属性      >> 1
当type为bar的时候，判断是否生成了默认属性        >> 1
当type为pie的时候，判断是否生成了默认属性        >> 1
当type为piecircle的时候，判断是否生成了默认属性  >> 1
当type为waterpolo的时候，判断是否生成了默认属性  >> 1
当type为radar的时候，判断是否生成了默认属性      >> 1
当type为funnel的时候，判断是否生成了默认属性     >> 1
*/

$screen = new screenTest();

$typeList = array('line', 'table', 'bar', 'pie', 'piecircle', 'waterpolo', 'radar', 'funnel', 'xlabel');

$component1 = new stdclass();
$component2 = new stdclass();
$component3 = new stdclass();
$component4 = new stdclass();
$component5 = new stdclass();
$component6 = new stdclass();
$component7 = new stdclass();
$component8 = new stdclass();
$component9 = new stdclass();
$componentList = array($component1, $component2, $component3, $component4, $component5, $component6, $component7, $component8, $component9);

$screen->setChartDefaultTest($typeList[0], $componentList[0]);
r($componentList[0]->request && $componentList[0]->events && $componentList[0]->key && $componentList[0]->chartConfig && $componentList[0]->option) && p('') && e(1);  //当type为line的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[1], $componentList[1]);
r($componentList[1]->request && $componentList[1]->events && $componentList[1]->key && $componentList[1]->chartConfig && $componentList[1]->option) && p('') && e(1);  //当type为table的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[2], $componentList[2]);
r($componentList[2]->request && $componentList[2]->events && $componentList[2]->key && $componentList[2]->chartConfig && $componentList[2]->option) && p('') && e(1);  //当type为bar的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[3], $componentList[3]);
r($componentList[3]->request && $componentList[3]->events && $componentList[3]->key && $componentList[3]->chartConfig && $componentList[3]->option) && p('') && e(1);  //当type为pie的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[4], $componentList[4]);
r($componentList[4]->request && $componentList[4]->events && $componentList[4]->key && $componentList[4]->chartConfig && $componentList[4]->option) && p('') && e(1);  //当type为piecircle的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[5], $componentList[5]);
r($componentList[5]->request && $componentList[5]->events && $componentList[5]->key && $componentList[5]->chartConfig && $componentList[5]->option) && p('') && e(1);  //当type为waterpolo的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[6], $componentList[6]);
r($componentList[6]->request && $componentList[6]->events && $componentList[6]->key && $componentList[6]->chartConfig && $componentList[6]->option) && p('') && e(1);  //当type为radar的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[7], $componentList[7]);
r($componentList[7]->request && $componentList[7]->events && $componentList[7]->key && $componentList[7]->chartConfig && $componentList[7]->option) && p('') && e(1);  //当type为funnel的时候，判断是否生成了默认属性

$screen->setChartDefaultTest($typeList[8], $componentList[8]);
r(isset($componentList[8]->request) && $componentList[8]->events && $componentList[8]->key && $componentList[8]->chartConfig && $componentList[8]->option) && p('') && e(0);  //当type为xlabel的时候，判断是否生成了默认属性
