#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);
zdTable('space')->gen(5);
zdTable('solution')->gen(5);

/**

title=instanceModel->instanceOfSolution();
timeout=0
cid=1

- 查看获取到的第一条instance
 - 属性id @1
 - 属性name @Subversion
 - 属性chart @subversion
- 查看获取到的第一条instance的space
 - 属性id @1
 - 属性name @默认空间1
- 查看获取到的第一条instance的solution
 - 属性id @1
 - 属性name @解决方案1
- 查看获取到的第二条instance
 - 属性id @2
 - 属性name @禅道开源版
 - 属性chart @zentao
- 查看获取到的第二条instance的space
 - 属性id @2
 - 属性name @默认空间2
- 查看获取到的第二条instance的solution
 - 属性id @2
 - 属性name @解决方案2
- 查看不存在的instance @0
- 查看不存在的instance @0

*/

global $tester;
$tester->loadModel('instance');

$solution = new stdclass();
$solution->id = 1;
$instance = $tester->instance->instanceOfSolution($solution, 'subversion');

r($instance)               && p('id,name,chart') && e('1,Subversion,subversion'); // 查看获取到的第一条instance
r($instance->spaceData)    && p('id,name')       && e('1,默认空间1'); // 查看获取到的第一条instance的space
r($instance->solutionData) && p('id,name')       && e('1,解决方案1'); // 查看获取到的第一条instance的solution

$solution->id = 2;
$instance = $tester->instance->instanceOfSolution($solution, 'zentao');

r($instance)               && p('id,name,chart') && e('2,禅道开源版,zentao'); // 查看获取到的第二条instance
r($instance->spaceData)    && p('id,name')       && e('2,默认空间2'); // 查看获取到的第二条instance的space
r($instance->solutionData) && p('id,name')       && e('2,解决方案2'); // 查看获取到的第二条instance的solution

$solution->id = 10000;
$instance = $tester->instance->instanceOfSolution($solution, '');
r($instance) && p() && e('0'); // 查看不存在的instance

$solution->id = 3;
$instance = $tester->instance->instanceOfSolution($solution, 'qucheng');
r($instance) && p() && e('0'); // 查看不存在的instance