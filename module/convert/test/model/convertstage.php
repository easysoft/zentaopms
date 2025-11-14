#!/usr/bin/env php
<?php

/**

title=测试 convertModel::convertStage();
timeout=0
cid=15766

- 步骤1：带有有效relations参数的正常转换 @developing
- 步骤2：使用session中jiraRelation的正常转换 @developing
- 步骤3：未找到匹配状态的情况 @wait
- 步骤4：空参数的边界值测试 @wait
- 步骤5：relations为空数组但session有数据的情况 @developed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->convertStageTest('open', '1', array('zentaoStage1' => array('open' => 'developing')))) && p() && e('developing'); // 步骤1：带有有效relations参数的正常转换
r($convertTest->convertStageTest('in-progress', '1', array())) && p() && e('developing'); // 步骤2：使用session中jiraRelation的正常转换
r($convertTest->convertStageTest('unknown', '1', array('zentaoStage1' => array('open' => 'developing')))) && p() && e('wait'); // 步骤3：未找到匹配状态的情况
r($convertTest->convertStageTest('', '', array())) && p() && e('wait'); // 步骤4：空参数的边界值测试
r($convertTest->convertStageTest('done', '2', array())) && p() && e('developed'); // 步骤5：relations为空数组但session有数据的情况