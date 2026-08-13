#!/usr/bin/env php
<?php

/**

title=测试 systemModel::restore();
timeout=0
cid=18744

- 步骤1：正常实例恢复备份属性result @fail
- 步骤2：恢复操作消息字段属性message @CNE服务器出错
- 步骤3：恢复操作数据字段属性data @~~
- 步骤4：空实例对象恢复属性result @fail
- 步骤5：空备份名称恢复属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$spaceTable = zenData('ops_space');
$spaceTable->loadYaml('space_restore', false, 2)->gen(5);

$instanceTable = zenData('instance');
$instanceTable->loadYaml('instance_restore', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$systemTest = new systemModelTest();

// 5. 创建测试实例对象
$spaceData = new stdClass();
$spaceData->k8space = 'test-namespace';

$validInstance = new stdClass();
$validInstance->id = 1;
$validInstance->name = 'test-instance';
$validInstance->space = 1;
$validInstance->status = 'running';
$validInstance->deleted = '0';
$validInstance->k8name = 'zentao-1';
$validInstance->spaceData = $spaceData;
$validInstance->channel = '';

$emptyInstance = new stdClass();
$emptyInstance->spaceData = new stdClass();
$emptyInstance->spaceData->k8space = '';
$emptyInstance->k8name = '';
$emptyInstance->channel = '';

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($systemTest->restoreTest($validInstance, 'backup-20240909-001', 'admin')) && p('result') && e('fail'); // 步骤1：正常实例恢复备份
r($systemTest->restoreTest($validInstance, 'backup-20240909-002', 'admin')) && p('message') && e('CNE服务器出错'); // 步骤2：恢复操作消息字段
r($systemTest->restoreTest($validInstance, 'backup-20240909-003', 'admin')) && p('data') && e('~~'); // 步骤3：恢复操作数据字段
r($systemTest->restoreTest($emptyInstance, 'backup-20240909-004', 'admin')) && p('result') && e('fail'); // 步骤4：空实例对象恢复
r($systemTest->restoreTest($validInstance, '', 'admin')) && p('result') && e('fail'); // 步骤5：空备份名称恢复