#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getFormSchemaDescription();
timeout=0
cid=1

- 步骤1：有名称和字段时包含目标表单信息 @1
- 步骤2：有名称和字段时包含智能体名称 @1
- 步骤3：有字段时包含字段名 title @1
- 步骤4：字段不是数组时被忽略 @0
- 步骤5：空字段时仍包含返回 JSON 提示 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$prompt = (object)array('name' => '需求助手', 'targetForm' => 'story.create');
$fields = array
(
    'title' => array('label' => '需求标题'),
    'spec'  => array('label' => '需求描述'),
    'ghost' => 'skip'
);

$descWithFields = $aiTest->getFormSchemaDescriptionTest($prompt, $fields);
$descNoFields   = $aiTest->getFormSchemaDescriptionTest((object)array('name' => '', 'targetForm' => 'bug.create'), array());

r(strpos($descWithFields, $aiTest->instance->lang->ai->prompts->targetFormInfo) !== false) && p() && e('1');
r(strpos($descWithFields, '需求助手') !== false) && p() && e('1');
r(strpos($descWithFields, '- title') !== false) && p() && e('1');
r(strpos($descWithFields, '- ghost') !== false) && p() && e('0');
r(strpos($descNoFields, $aiTest->instance->lang->ai->prompts->returnJSONObject) !== false) && p() && e('1');
