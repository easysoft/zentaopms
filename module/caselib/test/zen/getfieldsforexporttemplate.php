#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getFieldsForExportTemplate();
timeout=0
cid=15547

- 步骤1：正常调用获取导出模板字段 @12
- 步骤2：验证返回的字段包含module字段属性module @所属模块
- 步骤3：验证返回的字段包含title字段属性title @用例名称
- 步骤4：验证返回的字段包含特殊字段typeValue属性typeValue @类型可选值列表
- 步骤5：验证返回的字段包含特殊字段stageValue属性stageValue @阶段可选值列表

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

su('admin');

$caselibTest = new caselibTest();

r($caselibTest->getFieldsForExportTemplateTest('count')) && p() && e('12'); // 步骤1：正常调用获取导出模板字段
r($caselibTest->getFieldsForExportTemplateTest()) && p('module') && e('所属模块'); // 步骤2：验证返回的字段包含module字段
r($caselibTest->getFieldsForExportTemplateTest()) && p('title') && e('用例名称'); // 步骤3：验证返回的字段包含title字段
r($caselibTest->getFieldsForExportTemplateTest()) && p('typeValue') && e('类型可选值列表'); // 步骤4：验证返回的字段包含特殊字段typeValue
r($caselibTest->getFieldsForExportTemplateTest()) && p('stageValue') && e('阶段可选值列表'); // 步骤5：验证返回的字段包含特殊字段stageValue