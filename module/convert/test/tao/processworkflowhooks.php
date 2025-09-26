#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processWorkflowHooks();
timeout=0
cid=0

步骤1：正常情况测试 >> update,story,status,completed
步骤2：空结果测试 >> 0
步骤3：字符串步骤值测试 >> in-progress
步骤4：数组步骤值测试 >> done
步骤5：钩子结构完整性验证 >> data,empty,id,equal

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step1')))),
    array('step1' => 'completed'),
    'story'
)) && p('0:action,0:table,0:fields:0:field,0:fields:0:param') && e('update,story,status,completed'); // 步骤1：正常情况测试

r($convertTest->processWorkflowHooksTest(
    array(),
    array('step1' => 'completed'),
    'story'
)) && p() && e('0'); // 步骤2：空结果测试

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step2')))),
    array('step2' => 'in-progress'),
    'task'
)) && p('0:fields:0:param') && e('in-progress'); // 步骤3：字符串步骤值测试

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step3')))),
    array('step3' => array('done', 'finished')),
    'bug'
)) && p('0:fields:0:param') && e('done'); // 步骤4：数组步骤值测试

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step4')))),
    array('step4' => 'closed'),
    'project'
)) && p('0:conditionType,0:sqlResult,0:wheres:0:field,0:wheres:0:operator') && e('data,empty,id,equal'); // 步骤5：钩子结构完整性验证