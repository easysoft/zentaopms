#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildDynamicSchema();
timeout=0
cid=1

- 步骤1：普通 schema 类型为 object @object
- 步骤2：标题优先使用智能体名称 @需求助手
- 步骤3：必填字段进入 required @title
- 步骤4：options 生成 enum 的首项 @text
- 步骤5：批量 schema 类型为 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$prompt = (object)array('name' => '需求助手', 'targetForm' => 'story.create');
$fields = array
(
    'title' => array('label' => '需求标题', 'required' => 1),
    'type'  => array('label' => '需求类型', 'options' => array(array('value' => 'text', 'text' => '文本'), array('value' => 'code', 'text' => '代码')))
);

$schema      = $aiTest->buildDynamicSchemaTest($fields, $prompt, false);
$batchSchema = $aiTest->buildDynamicSchemaTest($fields, $prompt, true);

r($schema) && p('type') && e('object');
r($schema) && p('title') && e('需求助手');
r($schema) && p('required:0') && e('title');
r(json_encode($schema['properties']['type']['enum'])) && p() && e('["text","code"]');
r($batchSchema) && p('type') && e('array');
