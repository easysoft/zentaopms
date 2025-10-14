#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDefaultLanguageModel();
timeout=0
cid=0

- 步骤1：有启用模型时返回ID最小的启用模型属性id @1
- 步骤2：验证返回的是第一个启用模型的名称属性name @GPT-4
- 步骤3：验证返回的模型类型属性type @gpt
- 步骤4：验证返回的模型是启用状态属性enabled @1
- 步骤5：验证返回的模型未被删除属性deleted @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->getDefaultLanguageModelTest()) && p('id') && e('1'); // 步骤1：有启用模型时返回ID最小的启用模型
r($aiTest->getDefaultLanguageModelTest()) && p('name') && e('GPT-4'); // 步骤2：验证返回的是第一个启用模型的名称
r($aiTest->getDefaultLanguageModelTest()) && p('type') && e('gpt'); // 步骤3：验证返回的模型类型
r($aiTest->getDefaultLanguageModelTest()) && p('enabled') && e('1'); // 步骤4：验证返回的模型是启用状态
r($aiTest->getDefaultLanguageModelTest()) && p('deleted') && e('0'); // 步骤5：验证返回的模型未被删除