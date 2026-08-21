#!/usr/bin/env php
<?php

/**

title=测试 aiModel::loadContextFromFormSchema();
timeout=0
cid=1

- 步骤1：execution 字段可生成执行上下文 @1
- 步骤2：关联链可补出 project 和 product @1
- 步骤3：story 字段可生成需求上下文 @1
- 步骤4：执行对象明细被写入上下文 @1
- 步骤5：无效字段 requirementID 被忽略 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$objectMap = array
(
    'execution' => array(5 => (object)array('id' => 5, 'name' => '1.0 开发', 'project' => 3, 'begin' => '2026-01-01', 'end' => '2026-06-30', 'desc' => '执行描述', 'model' => 'scrum'),),
    'project'   => array(3 => (object)array('id' => 3, 'name' => '项目Alpha', 'begin' => '2026-01-01', 'end' => '2026-12-31', 'desc' => '项目描述'),),
    'product'   => array(1 => (object)array('id' => 1, 'name' => '产品A'),),
    'story'     => array(23 => (object)array('id' => 23, 'title' => '用户登录', 'spec' => '实现用户登录功能', 'pri' => '1', 'status' => 'active'),),
);

$formSchema = array
(
    'fields' => array
    (
        'execution' => array('name' => 'execution', 'currentValue' => '5'),
        'story'     => array('name' => 'story', 'currentValue' => '23'),
        'ghost'     => array('name' => 'requirementID', 'currentValue' => '9'),
    ),
);

$context = $aiTest->loadContextFromFormSchemaTest($formSchema, $objectMap);

/* 步骤1：execution 字段可生成执行上下文 */
$hasExecution = strpos($context, '执行：#5') !== false ? 1 : 0;
r($hasExecution) && p() && e('1'); // 步骤1：execution 字段可生成执行上下文

/* 步骤2：关联链可补出 project 和 product */
$hasProjectAndProduct = (strpos($context, '项目：#3') !== false && strpos($context, '产品：#1') !== false) ? 1 : 0;
r($hasProjectAndProduct) && p() && e('1'); // 步骤2：关联链可补出 project 和 product

/* 步骤3：story 字段可生成需求上下文 */
$hasStory = strpos($context, '需求：#23') !== false ? 1 : 0;
r($hasStory) && p() && e('1'); // 步骤3：story 字段可生成需求上下文

/* 步骤4：执行对象明细被写入上下文 */
$hasExecutionDetail = (strpos($context, 'name：1.0 开发') !== false && strpos($context, 'model：scrum') !== false) ? 1 : 0;
r($hasExecutionDetail) && p() && e('1'); // 步骤4：执行对象明细被写入上下文

/* 步骤5：无效字段 requirementID 被忽略 */
$hasRequirement = strpos($context, 'requirement') !== false ? 1 : 0;
r($hasRequirement) && p() && e('0'); // 步骤5：无效字段 requirementID 被忽略
