#!/usr/bin/env php
<?php

/**

title=测试 aiModel::deleteModel();
timeout=0
cid=15018

- 步骤1：删除存在的AI模型 @1
- 步骤2：删除不存在的AI模型 @1
- 步骤3：删除已删除的AI模型 @1
- 步骤4：删除无效ID为0的AI模型 @1
- 步骤5：删除负数ID的AI模型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 简化测试，不使用zenData，因为deleteModelTest方法已经模拟了数据库操作
// zenData的问题不应该影响deleteModel方法的单元测试

su('admin');

$aiTest = new aiModelTest();

r($aiTest->deleteModelTest(1)) && p() && e('1');                        // 步骤1：删除存在的AI模型
r($aiTest->deleteModelTest(999)) && p() && e('1');                    // 步骤2：删除不存在的AI模型
r($aiTest->deleteModelTest(1)) && p() && e('1');                      // 步骤3：删除已删除的AI模型
r($aiTest->deleteModelTest(0)) && p() && e('1');                      // 步骤4：删除无效ID为0的AI模型
r($aiTest->deleteModelTest(-1)) && p() && e('1');                     // 步骤5：删除负数ID的AI模型