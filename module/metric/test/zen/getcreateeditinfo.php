#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getCreateEditInfo();
timeout=0
cid=17190

- 步骤1：获取完整创建编辑信息字段数量 @4
- 步骤2：获取指定字段信息数量 @2
- 步骤3：获取单个字段的name信息第createdBy条的name属性 @创建者
- 步骤4：测试空字段参数 @0
- 步骤5：测试包含所有用户信息字段数量 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('metric')->loadYaml('metric_getcreateeditinfo', false, 2)->gen(10);
zendata('user')->loadYaml('user_getcreateeditinfo', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$view1 = new stdclass();
$view1->metric = new stdclass();
$view1->metric->id = 1;
$view1->metric->createdBy = 'admin';
$view1->metric->createdDate = '2024-01-01 10:00:00';
$view1->metric->implementedBy = 'admin';
$view1->metric->implementedDate = '2024-01-11 10:00:00';
$view1->metric->delistedBy = '';
$view1->metric->delistedDate = '';
$view1->metric->editedBy = 'admin';
$view1->metric->editedDate = '2024-03-01 10:00:00';

$view2 = new stdclass();
$view2->metric = new stdclass();
$view2->metric->id = 2;
$view2->metric->createdBy = 'user1';
$view2->metric->createdDate = '2024-01-02 11:00:00';
$view2->metric->implementedBy = 'user1';
$view2->metric->implementedDate = '2024-01-12 11:00:00';
$view2->metric->delistedBy = 'user2';
$view2->metric->delistedDate = '2024-02-12 11:00:00';
$view2->metric->editedBy = 'user1';
$view2->metric->editedDate = '2024-03-02 11:00:00';

r(count($metricZenTest->getCreateEditInfoZenTest($view1))) && p() && e('4'); // 步骤1：获取完整创建编辑信息字段数量
r(count($metricZenTest->getCreateEditInfoZenTest($view2, 'createdBy,offlineBy'))) && p() && e('2'); // 步骤2：获取指定字段信息数量
r($metricZenTest->getCreateEditInfoZenTest($view1, 'createdBy')) && p('createdBy:name') && e('创建者'); // 步骤3：获取单个字段的name信息
r(count($metricZenTest->getCreateEditInfoZenTest($view1, ''))) && p() && e('0'); // 步骤4：测试空字段参数
r(count($metricZenTest->getCreateEditInfoZenTest($view2, 'createdBy,implementedBy,offlineBy,lastEdited'))) && p() && e('4'); // 步骤5：测试包含所有用户信息字段数量