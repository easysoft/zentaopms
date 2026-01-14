#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::uninstall();
timeout=0
cid=16818

- 步骤1：验证external类型实例卸载 @1
- 步骤2：验证不同ID的external实例卸载 @1
- 步骤3：验证第三个external实例卸载 @1
- 步骤4：验证第四个external实例卸载 @1
- 步骤5：验证第五个external实例卸载 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->id->range('1-10');
$instanceTable->name->range('test_app{10}');
$instanceTable->source->range('external{10}');
$instanceTable->k8name->range('test-k8name{10}');
$instanceTable->status->range('running{5},stopped{5}');
$instanceTable->space->range('1-3:1');
$instanceTable->deleted->range('0{10}');
$instanceTable->gen(10);

$spaceTable = zenData('space');
$spaceTable->id->range('1-3');
$spaceTable->name->range('space{3}');
$spaceTable->k8space->range('ns-space{3}');
$spaceTable->deleted->range('0{3}');
$spaceTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 准备测试数据对象 - 都使用external类型避免CNE API调用
$instance1 = new stdClass();
$instance1->id = 1;
$instance1->source = 'external';

$instance2 = new stdClass();
$instance2->id = 2;
$instance2->source = 'external';

$instance3 = new stdClass();
$instance3->id = 3;
$instance3->source = 'external';

$instance4 = new stdClass();
$instance4->id = 4;
$instance4->source = 'external';

$instance5 = new stdClass();
$instance5->id = 5;
$instance5->source = 'external';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->uninstallTest($instance1)) && p('') && e('1'); // 步骤1：验证external类型实例卸载
r($instanceTest->uninstallTest($instance2)) && p('') && e('1'); // 步骤2：验证不同ID的external实例卸载
r($instanceTest->uninstallTest($instance3)) && p('') && e('1'); // 步骤3：验证第三个external实例卸载
r($instanceTest->uninstallTest($instance4)) && p('') && e('1'); // 步骤4：验证第四个external实例卸载
r($instanceTest->uninstallTest($instance5)) && p('') && e('1'); // 步骤5：验证第五个external实例卸载