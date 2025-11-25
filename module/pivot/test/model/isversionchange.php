#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isVersionChange();
timeout=0
cid=17413

- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot1, true 属性versionChange @1
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot2, true 属性versionChange @~~
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot3, true 属性versionChange @~~
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivots, false 
 - 第0条的versionChange属性 @1
 - 第0条的1:versionChange属性 @~~
- 执行pivotTest模块的isVersionChangeTest方法，参数是$pivot5, true 属性versionChange @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 直接在数据库中插入测试数据
global $tester;
$tester->dao->exec("DELETE FROM " . TABLE_PIVOTSPEC);
$tester->dao->exec("INSERT INTO " . TABLE_PIVOTSPEC . " (pivot, version) VALUES
    (1, '1.0'), (1, '1.1'), (1, '1.5'),
    (2, '2.0'), (2, '2.1'),
    (3, '1.0')");

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：内置pivot对象，版本号低于最新版本(1.0 < 1.5)
$pivot1 = new stdClass();
$pivot1->id = 1;
$pivot1->version = '1.0';
$pivot1->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot1, true)) && p('versionChange') && e('1');

// 测试步骤2：内置pivot对象，版本号等于最新版本(2.1 = 2.1)
$pivot2 = new stdClass();
$pivot2->id = 2;
$pivot2->version = '2.1';
$pivot2->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot2, true)) && p('versionChange') && e('~~');

// 测试步骤3：非内置pivot对象，即使版本差异也不需要更新
$pivot3 = new stdClass();
$pivot3->id = 3;
$pivot3->version = '1.0';
$pivot3->builtin = 0;
r($pivotTest->isVersionChangeTest($pivot3, true)) && p('versionChange') && e('~~');

// 测试步骤4：多个pivot对象数组测试
$pivots = array();
$pivots[0] = new stdClass();
$pivots[0]->id = 1;
$pivots[0]->version = '1.0';
$pivots[0]->builtin = 1;
$pivots[1] = new stdClass();
$pivots[1]->id = 2;
$pivots[1]->version = '2.1';
$pivots[1]->builtin = 1;
r($pivotTest->isVersionChangeTest($pivots, false)) && p('0:versionChange,1:versionChange') && e('1,~~');

// 测试步骤5：不存在的pivot ID，测试边界情况（没有版本记录时认为需要更新）
$pivot5 = new stdClass();
$pivot5->id = 999;
$pivot5->version = '1.0';
$pivot5->builtin = 1;
r($pivotTest->isVersionChangeTest($pivot5, true)) && p('versionChange') && e(1);