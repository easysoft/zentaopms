#!/usr/bin/env php
<?php

/**

title=测试 instanceZen::storeView();
timeout=0
cid=0

- 步骤1：正常访问有效实例属性result @success
- 步骤2：访问不存在的实例ID属性result @fail
- 步骤3：访问devops类型应用实例属性result @success
- 步骤4：访问运行状态的实例属性result @success
- 步骤5：正常访问实例测试权限属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('instance');
$table->id->range('1-10');
$table->space->range('1-3');
$table->name->range('zentao,jenkins,gitlab,test-app,demo-instance');
$table->appID->range('1-5');
$table->appName->range('禅道,Jenkins,GitLab,测试应用,演示实例');
$table->appVersion->range('1.0.0,2.1.0,3.0.1,4.2.3,5.1.2');
$table->chart->range('zentao,jenkins,gitlab,devops-toolkit,custom-app');
$table->status->range('running,stopped,installing,upgrading,uninstalling');
$table->domain->range('zentao.example.com,jenkins.example.com,gitlab.example.com,test.example.com,demo.example.com');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->storeViewTest(1)) && p('result') && e('success'); // 步骤1：正常访问有效实例
r($instanceTest->storeViewTest(999)) && p('result') && e('fail'); // 步骤2：访问不存在的实例ID
r($instanceTest->storeViewTest(3)) && p('result') && e('success'); // 步骤3：访问devops类型应用实例
r($instanceTest->storeViewTest(4)) && p('result') && e('success'); // 步骤4：访问运行状态的实例
r($instanceTest->storeViewTest(5)) && p('result') && e('success'); // 步骤5：正常访问实例测试权限