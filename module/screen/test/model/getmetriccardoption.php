#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getMetricCardOption();
timeout=0
cid=18245

- 执行screenTest模块的getMetricCardOptionTest方法，参数是1, array 属性displayType @normal
- 执行screenTest模块的getMetricCardOptionTest方法，参数是1, array 属性cardType @A
- 执行screenTest模块的getMetricCardOptionTest方法，参数是2, array 属性scope @system
- 执行screenTest模块的getMetricCardOptionTest方法，参数是4, array 第filterValue条的name属性 @test1
- 执行screenTest模块的getMetricCardOptionTest方法，参数是5, array 第filterValue条的status属性 @active

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('metric');
$table->id->range('1-5');
$table->name->range('用户统计,项目统计,任务统计,Bug统计,测试统计');
$table->scope->range('system{2},product{3}');
$table->dateType->range('nodate,day{2},week,month');
$table->gen(5);

su('admin');

$screenTest = new screenModelTest();

r($screenTest->getMetricCardOptionTest(1, array(), null)) && p('displayType') && e('normal');
r($screenTest->getMetricCardOptionTest(1, array(), null)) && p('cardType') && e('A');
r($screenTest->getMetricCardOptionTest(2, array(array('field1' => 'value1')), null)) && p('scope') && e('system');
r($screenTest->getMetricCardOptionTest(4, array(array('name' => 'test1'), array('name' => 'test2')), null)) && p('filterValue:name') && e('test1');
r($screenTest->getMetricCardOptionTest(5, array(array('id' => 1, 'status' => 'active')), null)) && p('filterValue:status') && e('active');