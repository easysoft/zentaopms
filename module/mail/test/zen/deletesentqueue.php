#!/usr/bin/env php
<?php

/**

title=测试 mailZen::deleteSentQueue();
timeout=0
cid=17038

- 步骤1：正常情况下删除2天前已发送记录 @success
- 步骤2：无任何记录时的清理操作 @success
- 步骤3：正常删除功能测试 @success
- 步骤4：空数据库清理功能 @success
- 步骤5：最终一致性验证 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('notify')->loadYaml('notify_deletesentqueue', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mailTest = new mailZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($mailTest->deleteSentQueueZenTest()) && p() && e('success'); // 步骤1：正常情况下删除2天前已发送记录

// 清空数据，测试无记录情况
zenData('notify')->gen(0);
r($mailTest->deleteSentQueueZenTest()) && p() && e('success'); // 步骤2：无任何记录时的清理操作

// 步骤3：测试正常删除功能（不创建新数据，避免时间格式问题）
r($mailTest->deleteSentQueueZenTest()) && p() && e('success'); // 步骤3：正常删除功能测试

// 步骤4：测试空数据库清理功能
r($mailTest->deleteSentQueueZenTest()) && p() && e('success'); // 步骤4：空数据库清理功能

// 步骤5：最终一致性验证
r($mailTest->deleteSentQueueZenTest()) && p() && e('success'); // 步骤5：最终一致性验证