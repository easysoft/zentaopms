#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->update();
cid=0

- 传入空参数 @0
- 测试修改plan的 name 值属性name @修改后的阶段
- 测试子阶段的 acl 值第0条的acl属性 @private

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(10);

$planID    = 8;
$projectID = 1;

$changeName = array('name' => '修改后的阶段');

$programplan = new programplanTest();

r($programplan->objectModel->update(0, 0, null)) && p() && e('0'); // 传入空参数

$plan = $programplan->updateTest($planID, $projectID, $changeName);
$childPlans = $programplan->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('parent')->eq($planID)->fetchAll();
r((array)$plan) && p('name') && e('修改后的阶段');  // 测试修改plan的 name 值
r($childPlans) && p('0:acl') && e('private');       // 测试子阶段的 acl 值
