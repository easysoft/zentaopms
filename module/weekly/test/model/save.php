#!/usr/bin/env php
<?php

/**

title=测试 weeklyModel::save();
timeout=0
cid=19739

- 执行weeklyTest模块的saveTest方法，参数是1, '2023-10-15'
 - 属性project @1
 - 属性weekStart @2023-10-09
- 执行weeklyTest模块的saveTest方法，参数是0, '2023-10-15'
 - 属性project @0
 - 属性weekStart @2023-10-09
- 执行weeklyTest模块的saveTest方法，参数是2, '2023-10-15'
 - 属性project @2
 - 属性weekStart @2023-10-09
- 执行weeklyTest模块的saveTest方法，参数是3, '2023-10-15'
 - 属性project @3
 - 属性weekStart @2023-10-09
- 执行weeklyTest模块的saveTest方法，参数是5, '2023-10-15'
 - 属性project @5
 - 属性weekStart @2023-10-09

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('weeklyreport')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTest = new weeklyModelTest();

r($weeklyTest->saveTest(1, '2023-10-15')) && p('project,weekStart') && e('1,2023-10-09');
r($weeklyTest->saveTest(0, '2023-10-15')) && p('project,weekStart') && e('0,2023-10-09');
r($weeklyTest->saveTest(2, '2023-10-15')) && p('project,weekStart') && e('2,2023-10-09');
r($weeklyTest->saveTest(3, '2023-10-15')) && p('project,weekStart') && e('3,2023-10-09');
r($weeklyTest->saveTest(5, '2023-10-15')) && p('project,weekStart') && e('5,2023-10-09');