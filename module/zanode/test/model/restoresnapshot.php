#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::restoreSnapshot();
timeout=0
cid=0

- 步骤1：HTTP连接失败，completed状态快照 @执行失败，请检查宿主机和执行节点状态
- 步骤2：快照正在还原中 @快照正在还原中
- 步骤3：快照状态为creating @快照不可用
- 步骤4：快照状态为running @快照不可用
- 步骤5：快照状态为failed @快照不可用

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('host')->loadYaml('host')->gen(5);
zenData('image')->loadYaml('image')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($zanodeTest->restoreSnapshotTest(2, 1)) && p() && e('执行失败，请检查宿主机和执行节点状态'); // 步骤1：HTTP连接失败，completed状态快照
r($zanodeTest->restoreSnapshotTest(2, 2)) && p('name:0') && e('快照正在还原中'); // 步骤2：快照正在还原中
r($zanodeTest->restoreSnapshotTest(2, 3)) && p('name:0') && e('快照不可用'); // 步骤3：快照状态为creating
r($zanodeTest->restoreSnapshotTest(2, 4)) && p('name:0') && e('快照不可用'); // 步骤4：快照状态为running
r($zanodeTest->restoreSnapshotTest(2, 5)) && p('name:0') && e('快照不可用'); // 步骤5：快照状态为failed