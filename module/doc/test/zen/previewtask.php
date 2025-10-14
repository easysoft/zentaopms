#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewTask();
timeout=0
cid=0

- 步骤1：正常预览执行任务（设置视图） @3
- 步骤2：列表视图显示任务列表 @3
- 步骤3：空执行ID情况 @0
- 步骤4：无效ID列表情况 @0
- 步骤5：其他action类型处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（不需要实际数据库，模拟数据即可）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewTaskTest('setting', array('action' => 'preview', 'execution' => 1), '')) && p() && e('3'); // 步骤1：正常预览执行任务（设置视图）
r($docTest->previewTaskTest('list', array('action' => 'list'), '1,2,3')) && p() && e('3'); // 步骤2：列表视图显示任务列表  
r($docTest->previewTaskTest('setting', array('action' => 'preview', 'execution' => 0), '')) && p() && e('0'); // 步骤3：空执行ID情况
r($docTest->previewTaskTest('list', array('action' => 'list'), 'abc,xyz')) && p() && e('0'); // 步骤4：无效ID列表情况
r($docTest->previewTaskTest('setting', array('action' => 'other', 'execution' => 1), '')) && p() && e('0'); // 步骤5：其他action类型处理