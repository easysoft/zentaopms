#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::updateCpuSize();
timeout=0
cid=16820

- 步骤1：正常CPU更新（CNE API失败） @调整CPU失败
- 步骤2：CPU设为0边界值（CNE API失败） @调整CPU失败
- 步骤3：较大CPU值（CNE API失败） @调整CPU失败
- 步骤4：字符串类型CPU值（CNE API失败） @调整CPU失败
- 步骤5：小数字符串CPU值（CNE API失败） @调整CPU失败

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->space->range('1');
$instanceTable->name->range('test-app1,test-app2,test-app3,test-app4,test-app5');
$instanceTable->appID->range('1,2,3,4,5');
$instanceTable->appName->range('zentao,gitlab,jenkins,sonar,nexus');
$instanceTable->chart->range('zentao,gitlab,jenkins,sonar,nexus');
$instanceTable->status->range('running');
$instanceTable->k8name->range('zentao-1,zentao-2,zentao-3,zentao-4,zentao-5');
$instanceTable->domain->range('test1.example.com,test2.example.com,test3.example.com,test4.example.com,test5.example.com');
$instanceTable->deleted->range('0');
$instanceTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试实例对象（包含必要属性）
$spaceData = new stdclass();
$spaceData->id = 1;
$spaceData->k8space = 'test-namespace';

$instance1 = new stdclass();
$instance1->id = 1;
$instance1->oldValue = 1;
$instance1->spaceData = $spaceData;
$instance1->k8name = 'zentao-1';
$instance1->chart = 'zentao';

$instance2 = new stdclass();
$instance2->id = 2;
$instance2->oldValue = 2;
$instance2->spaceData = $spaceData;
$instance2->k8name = 'zentao-2';
$instance2->chart = 'gitlab';

$instance3 = new stdclass();
$instance3->id = 3;
$instance3->oldValue = 4;
$instance3->spaceData = $spaceData;
$instance3->k8name = 'zentao-3';
$instance3->chart = 'jenkins';

$instance4 = new stdclass();
$instance4->id = 4;
$instance4->oldValue = 2;
$instance4->spaceData = $spaceData;
$instance4->k8name = 'zentao-4';
$instance4->chart = 'sonar';

$instance5 = new stdclass();
$instance5->id = 5;
$instance5->oldValue = 1;
$instance5->spaceData = $spaceData;
$instance5->k8name = 'zentao-5';
$instance5->chart = 'nexus';

r($instanceTest->updateCpuSizeTest($instance1, 2)) && p('0') && e('调整CPU失败'); // 步骤1：正常CPU更新（CNE API失败）
r($instanceTest->updateCpuSizeTest($instance2, 0)) && p('0') && e('调整CPU失败'); // 步骤2：CPU设为0边界值（CNE API失败）
r($instanceTest->updateCpuSizeTest($instance3, 16)) && p('0') && e('调整CPU失败'); // 步骤3：较大CPU值（CNE API失败）
r($instanceTest->updateCpuSizeTest($instance4, "4")) && p('0') && e('调整CPU失败'); // 步骤4：字符串类型CPU值（CNE API失败）
r($instanceTest->updateCpuSizeTest($instance5, "0.5")) && p('0') && e('调整CPU失败'); // 步骤5：小数字符串CPU值（CNE API失败）