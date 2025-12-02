#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::getParentStages();
timeout=0
cid=17769

- 测试步骤1：正常情况查询属性2 @执行1-1
- 测试步骤2：不存在的项目ID @0
- 测试步骤3：产品ID为0属性2 @执行1-1
- 测试步骤4：使用noclosed参数属性3 @执行1-1-1
- 测试步骤5：其他项目查询 @0
- 测试步骤6：边界值executionID为0 @0
- 测试步骤7：异常值executionID为负数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 准备测试数据
zenData('project')->loadYaml('project')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);

// 登录admin用户
su('admin');

// 创建测试实例
$programplanTest = new programplanTest();

r($programplanTest->getParentStagesTest(1, 10, 2)) && p('2') && e('执行1-1');             // 测试步骤1：正常情况查询
r($programplanTest->getParentStagesTest(999, 10, 2)) && p() && e('0');                    // 测试步骤2：不存在的项目ID
r($programplanTest->getParentStagesTest(1, 5, 0)) && p('2') && e('执行1-1');              // 测试步骤3：产品ID为0
r($programplanTest->getParentStagesTest(1, 8, 3, 'noclosed')) && p('3') && e('执行1-1-1'); // 测试步骤4：使用noclosed参数
r($programplanTest->getParentStagesTest(2, 12, 4)) && p() && e('0');                      // 测试步骤5：其他项目查询
r($programplanTest->getParentStagesTest(0, 1, 1)) && p() && e('0');                       // 测试步骤6：边界值executionID为0
r($programplanTest->getParentStagesTest(-1, 1, 1)) && p() && e('0');                      // 测试步骤7：异常值executionID为负数