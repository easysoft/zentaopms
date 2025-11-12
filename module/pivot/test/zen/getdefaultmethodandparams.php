#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getDefaultMethodAndParams();
timeout=0
cid=0

- 执行pivotTest模块的getDefaultMethodAndParamsTest方法，参数是1, 0  @~~
- 执行pivotTest模块的getDefaultMethodAndParamsTest方法，参数是1, 999  @~~
- 执行pivotTest模块的getDefaultMethodAndParamsTest方法，参数是1, 1  @~~
- 执行pivotTest模块的getDefaultMethodAndParamsTest方法，参数是1, 5  @~~
- 执行pivotTest模块的getDefaultMethodAndParamsTest方法，参数是1, 9  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1');
$table->branch->range('0');
$table->name->range('产品分组,项目分组,测试分组,员工分组,子分组1,子分组2,产品分组空,项目分组无权限,空分组,测试分组2');
$table->parent->range('0,0,0,0,1,2,0,0,0,0');
$table->path->range("',1,',',2,',',3,',',4,',',1,5,',',2,6,',',7,',',8,',',9,',',10,'");
$table->grade->range('1,1,1,1,2,2,1,1,1,1');
$table->order->range('1-10');
$table->type->range('pivot');
$table->owner->range('admin');
$table->collector->range('product,project,test,staff,product,project,product,project,empty,test');
$table->deleted->range('0');
$table->gen(10);

zenData('user')->gen(1);
zenData('group')->gen(1);
zenData('usergroup')->gen(1);
zenData('grouppriv')->gen(100);
zenData('dimension')->gen(1);
zenData('pivot')->gen(0);

su('admin');

global $app;
$app->loadLang('pivot');

$pivotTest = new pivotZenTest();

r($pivotTest->getDefaultMethodAndParamsTest(1, 0)) && p('0') && e('~~');
r($pivotTest->getDefaultMethodAndParamsTest(1, 999)) && p('0') && e('~~');
r($pivotTest->getDefaultMethodAndParamsTest(1, 1)) && p('0') && e('~~');
r($pivotTest->getDefaultMethodAndParamsTest(1, 5)) && p('0') && e('~~');
r($pivotTest->getDefaultMethodAndParamsTest(1, 9)) && p('0') && e('~~');