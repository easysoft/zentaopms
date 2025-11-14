#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(5);
zenData('space')->gen(5);

/**

title=instanceModel->getByID();
timeout=0
cid=16798

- 查看获取到的第一条instance
 - 属性id @1
 - 属性name @Subversion
 - 属性chart @subversion
- 查看获取到的第一条instance的space
 - 属性id @1
 - 属性name @默认空间1
- 查看获取到的第一条instance的solution
 - 属性id @1
- 查看获取到的第二条instance
 - 属性id @2
 - 属性name @禅道开源版
 - 属性chart @zentao
- 查看获取到的第二条instance的space
 - 属性id @2
 - 属性name @默认空间2
- 查看获取到的第二条instance的solution
 - 属性id @2
- 查看不存在的instance @0

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByID(1);
r($instance)               && p('id,name,chart') && e('1,Subversion,subversion'); // 查看获取到的第一条instance
r($instance->spaceData)    && p('id,name')       && e('1,默认空间1'); // 查看获取到的第一条instance的space

$instance = $tester->instance->getByID(2);
r($instance)               && p('id,name,chart') && e('2,禅道开源版,zentao'); // 查看获取到的第二条instance
r($instance->spaceData)    && p('id,name')       && e('2,默认空间2'); // 查看获取到的第二条instance的space

$instance = $tester->instance->getByID(10000);
r($instance) && p('') && e('0'); // 查看不存在的instance
