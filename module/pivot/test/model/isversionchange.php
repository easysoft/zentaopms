#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isVersionChange();
timeout=0
cid=0

- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot1, true 属性versionChange @1
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot2, true 属性versionChange @0
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot3, true 属性versionChange @0
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivots, false 
 - 第0条的versionChange属性 @1
 - 第0条的1:versionChange属性 @0
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot5, true 属性versionChange @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTable = zenData('pivot');
$pivotTable->id->range('1-5');
$pivotTable->name->range('测试透视表{1-5}');
$pivotTable->builtin->range('1{3},0{2}');
$pivotTable->version->range('1.0{2},2.0{3}');
$pivotTable->deleted->range('0');
$pivotTable->gen(5);

$pivotspecTable = zenData('pivotspec');
$pivotspecTable->pivot->range('1{3},2{4},3{3}');
$pivotspecTable->version->range('1.0,1.1,1.5,2.0{4},2.1{3}');
$pivotspecTable->gen(10);

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：内置pivot对象，版本号低于最新版本
$pivot1 = new stdClass();
$pivot1->id = 1;
$pivot1->version = '1.0';
$pivot1->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot1, true)) && p('versionChange') && e('1');

// 测试步骤2：内置pivot对象，版本号等于最新版本
$pivot2 = new stdClass();
$pivot2->id = 2;
$pivot2->version = '2.0';
$pivot2->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot2, true)) && p('versionChange') && e('0');

// 测试步骤3：非内置pivot对象，无论版本差异
$pivot3 = new stdClass();
$pivot3->id = 3;
$pivot3->version = '1.0';
$pivot3->builtin = 0;
r($pivotTest->isVersionChangeTest($pivot3, true)) && p('versionChange') && e('0');

// 测试步骤4：多个pivot对象数组，isObject为false
$pivots = array();
$pivots[0] = new stdClass();
$pivots[0]->id = 1;
$pivots[0]->version = '1.0';
$pivots[0]->builtin = 1;
$pivots[1] = new stdClass();
$pivots[1]->id = 2;
$pivots[1]->version = '2.0';
$pivots[1]->builtin = 1;
r($pivotTest->isVersionChangeTest($pivots, false)) && p('0:versionChange,1:versionChange') && e('1,0');

// 测试步骤5：空ID的pivot对象，测试边界情况
$pivot5 = new stdClass();
$pivot5->id = 999;
$pivot5->version = '1.0';
$pivot5->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot5, true)) && p('versionChange') && e('0');