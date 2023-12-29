#!/usr/bin/env php
<?php

/**

title=测试 loadModel->updateRow()
cid=0

- 传入空参数 @0
- 检查未编辑前的数据。
 - 属性name @执行3-1
 - 属性attribute @``
 - 属性version @0
- 测试修改plan的 name 和 version 值。
 - 属性name @修改后的阶段
 - 属性version @1
- 测试查看项目下的spec信息。 @1
- 测试修改plan的 attribute 值。
 - 属性name @修改后的阶段
 - 属性attribute @devel
 - 属性version @1

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->app->user->admin = true;

$planID    = 8;
$projectID = 1;

$changeName = array('name' => '修改后的阶段');

$programplan = new programplanTest();

r($programplan->objectModel->update(0, 0, null)) && p() && e('0'); // 传入空参数
$rawPlan = $programplan->objectModel->getByID($planID);
r((array)$rawPlan)  && p('name,attribute,version') && e('执行3-1,``,0');  // 检查未编辑前的数据。

$plan = $programplan->updateRowTest($planID, $projectID, $changeName);
$spec = $programplan->objectModel->dao->select('*')->from(TABLE_PROJECTSPEC)->where('project')->eq($planID)->andWhere('version')->eq($plan->version)->fetch();
r((array)$plan)  && p('name,version') && e('修改后的阶段,1');  // 测试修改plan的 name 和 version 值。
r(!empty($spec)) && p()               && e('1');               // 测试查看项目下的spec信息。

$plan = $programplan->updateRowTest($planID, $projectID, array('attribute' => 'devel'));
r((array)$plan)  && p('name,attribute,version') && e('修改后的阶段,devel,1');  // 测试修改plan的 attribute 值。
