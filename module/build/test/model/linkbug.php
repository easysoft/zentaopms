#!/usr/bin/env php
<?php
/**

title=测试 buildModel->linkBug();
timeout=0
cid=15503

- 测试版本1关联Bug2,3
 - 第0条的field属性 @bugs
 - 第0条的old属性 @1
 - 第0条的new属性 @1,2,3
- 测试版本2关联Bug5,6
 - 第0条的field属性 @bugs
 - 第0条的old属性 @2
 - 第0条的new属性 @2,5,6
- 不存在版本 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$build = zenData('build')->loadYaml('build');
$build->bugs->range('1-5');
$build->gen(5);

zenData('user')->gen(5);
su('admin');

$builds  = array(1, 2, 10);
$bugs[0] = array(2, 3);
$bugs[1] = array(5, 6);

$buildTester = new buildModelTest();

r($buildTester->linkBugTest($builds[0], $bugs[0])) && p('0:field;0:old;0:new', ';') && e('bugs;1;1,2,3'); // 测试版本1关联Bug2,3
r($buildTester->linkBugTest($builds[1], $bugs[1])) && p('0:field;0:old;0:new', ';') && e('bugs;2;2,5,6'); // 测试版本2关联Bug5,6
r($buildTester->linkBugTest($builds[2], $bugs[0])) && p()                           && e('0');            // 不存在版本
