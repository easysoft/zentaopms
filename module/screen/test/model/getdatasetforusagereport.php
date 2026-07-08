#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getDatasetForUsageReport();
timeout=0
cid=18241

- 步骤1：chartID 20002(活跃用户表格) @0
- 步骤2：chartID 20004(活跃产品卡片)第0条的count属性 @0
- 步骤3：chartID 20007(活跃项目卡片)第0条的count属性 @0
- 步骤4：chartID 20010(项目任务表格) @0
- 步骤5：不存在的chartID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于数据复杂性，先不设置过多测试数据，主要测试方法逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->getDatasetForUsageReportTest(20002)) && p() && e('0'); // 步骤1：chartID 20002(活跃用户表格)
r($screenTest->getDatasetForUsageReportTest(20004)) && p('0:count') && e('0'); // 步骤2：chartID 20004(活跃产品卡片)
r($screenTest->getDatasetForUsageReportTest(20007)) && p('0:count') && e('0'); // 步骤3：chartID 20007(活跃项目卡片)
r($screenTest->getDatasetForUsageReportTest(20010)) && p() && e('0'); // 步骤4：chartID 20010(项目任务表格)
r(is_null($screenTest->getDatasetForUsageReportTest(99999))) && p() && e('1'); // 步骤5：不存在的chartID
