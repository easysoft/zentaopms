#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getObjectDefaultValue();
timeout=0
cid=15786

- 步骤1：测试object步骤返回配置数组第zentaoObject条的Epic属性 @requirement
- 步骤2：测试有效步骤ID返回默认值数组第zentaoStatus1001条的Open属性 @draft
- 步骤3：测试无效步骤ID返回空数组 @0
- 步骤4：测试空字符串返回空数组 @0
- 步骤5：测试空jiraRelation返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getObjectDefaultValueTest('object', array())) && p('zentaoObject:Epic') && e('requirement'); // 步骤1：测试object步骤返回配置数组
r($convertTest->getObjectDefaultValueTest('1001', array('jiraRelation' => json_encode(array('zentaoObject' => array('1001' => 'story')))))) && p('zentaoStatus1001:Open') && e('draft'); // 步骤2：测试有效步骤ID返回默认值数组
r($convertTest->getObjectDefaultValueTest('9999', array('jiraRelation' => json_encode(array('zentaoObject' => array('1001' => 'story')))))) && p() && e('0'); // 步骤3：测试无效步骤ID返回空数组
r($convertTest->getObjectDefaultValueTest('', array('jiraRelation' => json_encode(array('zentaoObject' => array('1001' => 'story')))))) && p() && e('0'); // 步骤4：测试空字符串返回空数组
r($convertTest->getObjectDefaultValueTest('1001', array('jiraRelation' => ''))) && p() && e('0'); // 步骤5：测试空jiraRelation返回空数组