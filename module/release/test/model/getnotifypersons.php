#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->getNotifyPersons();
timeout=0
cid=17995

- 测试获取状态为正常的发布的通知人员属性admin @admin
- 测试获取状态为停止维护的发布的通知人员 @0
- 测试获取notify为CT的发布的通知人员 @admin
- 测试获取已删除的发布的通知人员 @0
- 测试获取notify为PO并且PO 为admin的发布的通知人员属性admin @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$release = zenData('release')->loadYaml('release');
$release->stories->range('`1,2,3`,[]');
$release->bugs->range('`1,2,3`,[]');
$release->notify->range('`PO,QD,feedback`,`SC,ET,PT`,`CT`,``');
$release->status->range('normal,terminate');
$release->mailto->range('admin,test');
$release->deleted->range('0{3},1');
$release->gen(5);

zenData('story')->loadYaml('story')->gen(5);
zenData('bug')->loadYaml('bug')->gen(5);
zenData('product')->loadYaml('product')->gen(5);
zenData('build')->loadYaml('build')->gen(5);
zenData('team')->gen(0);
zenData('user')->gen(5);
su('admin');

$releases = array(1, 2, 3, 4, 5);

$releaseTester = new releaseTest();
r($releaseTester->getNotifyPersonsTest($releases[0])) && p('admin') && e('admin'); // 测试获取状态为正常的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[1])) && p()        && e('0');     // 测试获取状态为停止维护的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[2])) && p('0')     && e('admin'); // 测试获取notify为CT的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[3])) && p()        && e('0');     // 测试获取已删除的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[4])) && p('admin') && e('admin'); // 测试获取notify为PO并且PO 为admin的发布的通知人员