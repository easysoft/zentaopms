#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::deleteBackup();
timeout=0
cid=16788

- 步骤1：有效参数删除备份属性code @600
- 步骤2：空备份名称属性code @600
- 步骤3：不存在的备份名称属性code @600
- 步骤4：无效实例属性code @600
- 步骤5：特殊字符备份名称属性code @600

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->id->range('1-10');
$instanceTable->name->range('test-instance1,test-instance2,test-instance3,demo-instance1,demo-instance2');
$instanceTable->domain->range('test1.example.com,test2.example.com,test3.example.com');
$instanceTable->status->range('running,stopped,initializing');
$instanceTable->chart->range('zentao,gitlab,jenkins');
$instanceTable->deleted->range('0');
$instanceTable->gen(10);

$spaceTable = zenData('space');
$spaceTable->id->range('1-5');
$spaceTable->name->range('space1,space2,space3,space4,space5');
$spaceTable->k8space->range('ns-1,ns-2,ns-3,ns-4,ns-5');
$spaceTable->deleted->range('0');
$spaceTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 创建测试用的实例对象
$validInstance = new stdClass();
$validInstance->id = 1;
$validInstance->name = 'test-instance';
$validInstance->domain = 'test.example.com';
$validInstance->chart = 'zentao';
$validInstance->k8name = 'test-k8name';
$validInstance->spaceData = new stdClass();
$validInstance->spaceData->k8space = 'test-namespace';

$invalidInstance = new stdClass();
$invalidInstance->id = 0;
$invalidInstance->spaceData = new stdClass();
$invalidInstance->spaceData->k8space = 'invalid-namespace';
$invalidInstance->k8name = 'invalid-k8name';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->deleteBackupTest($validInstance, 'valid-backup-name')) && p('code') && e('600'); // 步骤1：有效参数删除备份
r($instanceTest->deleteBackupTest($validInstance, '')) && p('code') && e('600'); // 步骤2：空备份名称
r($instanceTest->deleteBackupTest($validInstance, 'nonexistent-backup')) && p('code') && e('600'); // 步骤3：不存在的备份名称
r($instanceTest->deleteBackupTest($invalidInstance, 'backup-name')) && p('code') && e('600'); // 步骤4：无效实例
r($instanceTest->deleteBackupTest($validInstance, 'backup-with-special-chars-@#$%')) && p('code') && e('600'); // 步骤5：特殊字符备份名称