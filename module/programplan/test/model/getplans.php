#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getPlans();
cid=0

- 测试获取执行2 产品2的阶段
 - 属性type @stage
 - 属性name @阶段a
- 测试获取执行3 产品2的阶段
 - 属性type @stage
 - 属性name @阶段a子1
- 测试获取执行4 产品2的阶段
 - 属性type @stage
 - 属性name @阶段a子1子1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('product')->gen(5);
zdTable('projectproduct')->config('projectproduct')->gen(5);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->app->user->admin = true;

r($tester->programplan->getPlans(2, 2)[2]) && p('type,name') && e('stage,阶段a');       // 测试获取执行2 产品2的阶段
r($tester->programplan->getPlans(3, 2)[3]) && p('type,name') && e('stage,阶段a子1');    // 测试获取执行3 产品2的阶段
r($tester->programplan->getPlans(4, 2)[4]) && p('type,name') && e('stage,阶段a子1子1'); // 测试获取执行4 产品2的阶段
