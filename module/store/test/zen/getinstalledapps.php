#!/usr/bin/env php
<?php

/**

title=测试 storeZen::getInstalledApps();
timeout=0
cid=18459

- 步骤1：正常情况 - admin用户默认空间有3个已安装应用 @3
- 步骤2：验证admin用户空间第一个应用的appID @101
- 步骤3：验证admin用户空间第二个应用的appID属性1 @102
- 步骤4：切换到user1用户 - 有2个已安装应用 @2
- 步骤5：验证user1用户空间第一个应用的appID @104

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$space = zenData('space');
$space->id->range('1-5');
$space->name->range('AdminSpace,User1Space,User2Space,User3Space,User4Space');
$space->owner->range('admin,user1,user2,user3,user4');
$space->default->range('1{5}');
$space->deleted->range('0{5}');
$space->gen(5);

$instance = zenData('instance');
$instance->id->range('1-10');
$instance->space->range('1{3},2{2},3{2},4{2},5{1}');
$instance->appID->range('101-110');
$instance->name->range('App1,App2,App3,App4,App5,App6,App7,App8,App9,App10');
$instance->deleted->range('0{10}');
$instance->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storeTest = new storeZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storeTest->getInstalledAppsTest())) && p() && e('3'); // 步骤1：正常情况 - admin用户默认空间有3个已安装应用
r($storeTest->getInstalledAppsTest()) && p('0') && e('101'); // 步骤2：验证admin用户空间第一个应用的appID
r($storeTest->getInstalledAppsTest()) && p('1') && e('102'); // 步骤3：验证admin用户空间第二个应用的appID
su('user1');
r(count($storeTest->getInstalledAppsTest())) && p() && e('2'); // 步骤4：切换到user1用户 - 有2个已安装应用
r($storeTest->getInstalledAppsTest()) && p('0') && e('104'); // 步骤5：验证user1用户空间第一个应用的appID