#!/usr/bin/env php
<?php

/**

title=测试 systemModel::getBackupList();
timeout=0
cid=18730

- 步骤1：正常实例获取备份列表属性result @fail
- 步骤2：获取备份列表消息字段属性message @CNE服务器出错
- 步骤3：获取备份列表数据字段属性data @~~
- 步骤4：无效实例获取备份列表属性result @fail
- 步骤5：空实例获取备份列表属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$spaceTable = zenData('space');
$spaceTable->loadYaml('space_getbackuplist', false, 2)->gen(5);

$instanceTable = zenData('instance');
$instanceTable->loadYaml('instance_getbackuplist', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$systemTest = new systemTest();

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

$invalidSpaceData = new stdClass();
$invalidSpaceData->k8space = 'invalid-namespace';

$invalidInstance = new stdClass();
$invalidInstance->id = 999;
$invalidInstance->name = 'invalid-instance';
$invalidInstance->space = 999;
$invalidInstance->status = 'failed';
$invalidInstance->deleted = '1';
$invalidInstance->k8name = 'invalid-k8name';
$invalidInstance->spaceData = $invalidSpaceData;
$invalidInstance->channel = '';

$emptyInstance = new stdClass();
$emptyInstance->spaceData = new stdClass();
$emptyInstance->spaceData->k8space = '';
$emptyInstance->k8name = '';
$emptyInstance->channel = '';

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($systemTest->getBackupListTest($validInstance)) && p('result') && e('fail'); // 步骤1：正常实例获取备份列表
r($systemTest->getBackupListTest($validInstance)) && p('message') && e('CNE服务器出错'); // 步骤2：获取备份列表消息字段
r($systemTest->getBackupListTest($validInstance)) && p('data') && e('~~'); // 步骤3：获取备份列表数据字段
r($systemTest->getBackupListTest($invalidInstance)) && p('result') && e('fail'); // 步骤4：无效实例获取备份列表
r($systemTest->getBackupListTest($emptyInstance)) && p('result') && e('fail'); // 步骤5：空实例获取备份列表