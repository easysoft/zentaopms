#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getStageAttribute();
timeout=0
cid=17750

- 测试步骤1：获取id为1的阶段属性（空属性） @0
- 测试步骤2：获取id为2的阶段属性（项目集） @0
- 测试步骤3：获取id为3的阶段属性（项目集） @0
- 测试步骤4：获取id为5的阶段属性（项目集） @0
- 测试步骤5：获取不存在id为999的阶段属性 @0
- 测试步骤6：获取id为0的阶段属性（无效id） @0
- 测试步骤7：获取负数id的阶段属性（无效id） @0
- 测试步骤8：测试方法的类型安全性（边界值） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('getstageattribute/project')->gen(10);

su('admin');

$programplanTest = new programplanModelTest();

r($programplanTest->getStageAttributeTest(1))   && p() && e('0');    // 测试步骤1：获取id为1的阶段属性（空属性）
r($programplanTest->getStageAttributeTest(2))   && p() && e('0');    // 测试步骤2：获取id为2的阶段属性（项目集）
r($programplanTest->getStageAttributeTest(3))   && p() && e('0');    // 测试步骤3：获取id为3的阶段属性（项目集）
r($programplanTest->getStageAttributeTest(5))   && p() && e('0');    // 测试步骤4：获取id为5的阶段属性（项目集）
r($programplanTest->getStageAttributeTest(999)) && p() && e('0');    // 测试步骤5：获取不存在id为999的阶段属性
r($programplanTest->getStageAttributeTest(0))   && p() && e('0');    // 测试步骤6：获取id为0的阶段属性（无效id）
r($programplanTest->getStageAttributeTest(-1))  && p() && e('0');    // 测试步骤7：获取负数id的阶段属性（无效id）
r($programplanTest->getStageAttributeTest(10))  && p() && e('0');    // 测试步骤8：测试方法的类型安全性（边界值）