#!/usr/bin/env php
<?php
/**

title=测试 buildModel->unlinkBug();
timeout=0
cid=15508

- 测试版本跟Bug为空
 - 属性id @0
 - 属性bugs @0
- 测试解除版本1跟Bug1之间的关联
 - 属性id @1
 - 属性bugs @,2,3
- 测试解除版本2跟Bug4之间的关联
 - 属性id @2
 - 属性bugs @,5,6
- 测试版本不存在
 - 属性id @0
 - 属性bugs @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$build = zenData('build')->loadYaml('build');
$build->bugs->range('`1,2,3`,`4,5,6`');
$build->gen(5);

zenData('user')->gen(5);
su('admin');

$buildIdList = array(0, 1, 2, 6);
$bugIdList   = array(0, 1, 4);

$buildTester = new buildModelTest();
r($buildTester->unlinkBugTest($buildIdList[0], $bugIdList[0])) && p('id;bugs', ';') && e('0;0');    // 测试版本跟Bug为空
r($buildTester->unlinkBugTest($buildIdList[1], $bugIdList[1])) && p('id;bugs', ';') && e('1;,2,3'); // 测试解除版本1跟Bug1之间的关联
r($buildTester->unlinkBugTest($buildIdList[2], $bugIdList[2])) && p('id;bugs', ';') && e('2;,5,6'); // 测试解除版本2跟Bug4之间的关联
r($buildTester->unlinkBugTest($buildIdList[3], $bugIdList[1])) && p('id;bugs', ';') && e('0;0');    // 测试版本不存在
