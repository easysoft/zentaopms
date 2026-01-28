#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::checkLeafStage();
timeout=0
cid=17736

- 测试步骤1：正常输入有效阶段ID，测试叶子节点判断逻辑 @1
- 测试步骤2：正常输入有效阶段ID，测试叶子节点判断逻辑 @1
- 测试步骤3：边界值测试，输入0，空ID应返回false @0
- 测试步骤4：异常输入测试，输入负数ID，负数ID处理逻辑 @1
- 测试步骤5：异常输入测试，输入不存在的极大ID，不存在ID处理逻辑 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('checkleafstage/checkleafstage')->gen(10);

$programplanTest = new programplanModelTest();

r($programplanTest->checkLeafStageTest(1)) && p() && e('1');     // 测试步骤1：正常输入有效阶段ID，测试叶子节点判断逻辑
r($programplanTest->checkLeafStageTest(4)) && p() && e('1');      // 测试步骤2：正常输入有效阶段ID，测试叶子节点判断逻辑
r($programplanTest->checkLeafStageTest(0)) && p() && e('0');     // 测试步骤3：边界值测试，输入0，空ID应返回false
r($programplanTest->checkLeafStageTest(-1)) && p() && e('1');    // 测试步骤4：异常输入测试，输入负数ID，负数ID处理逻辑
r($programplanTest->checkLeafStageTest(999999)) && p() && e('1'); // 测试步骤5：异常输入测试，输入不存在的极大ID，不存在ID处理逻辑