#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getRowsForExportTemplate();
timeout=0
cid=15550

- 步骤1:传入2个模块和num=1,验证返回的行数应为2 @2
- 步骤2:传入3个模块和num=2,验证返回的行数应为6 @6
- 步骤3:传入num=2时应生成4行数据 @4
- 步骤4:传入空模块数组,验证返回空数组 @0
- 步骤5:验证num=0时返回空数组 @0
- 步骤6:验证第一行包含typeValue字段 @1
- 步骤7:验证第一行包含stageValue字段 @1
- 步骤8:验证第二行不包含typeValue字段第1条的typeValue属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

$module = zenData('module');
$module->loadYaml('module_getrowsforexporttemplate', false, 2)->gen(10);

su('admin');

$caselibTest = new caselibTest();

$modules1 = array(101 => '模块1', 102 => '模块2');
$modules2 = array(101 => '模块1', 102 => '模块2', 103 => '模块3');
$modules3 = array();

r($caselibTest->getRowsForExportTemplateTest(1, $modules1, 'count')) && p() && e('2'); // 步骤1:传入2个模块和num=1,验证返回的行数应为2
r($caselibTest->getRowsForExportTemplateTest(2, $modules2, 'count')) && p() && e('6'); // 步骤2:传入3个模块和num=2,验证返回的行数应为6
r($caselibTest->getRowsForExportTemplateTest(2, $modules1, 'count')) && p() && e('4'); // 步骤3:传入num=2时应生成4行数据
r($caselibTest->getRowsForExportTemplateTest(1, $modules3, 'count')) && p() && e('0'); // 步骤4:传入空模块数组,验证返回空数组
r($caselibTest->getRowsForExportTemplateTest(0, $modules1, 'count')) && p() && e('0'); // 步骤5:验证num=0时返回空数组
r($caselibTest->getRowsForExportTemplateTest(1, $modules1, 'first_hasTypeValue')) && p() && e('1'); // 步骤6:验证第一行包含typeValue字段
r($caselibTest->getRowsForExportTemplateTest(1, $modules1, 'first_hasStageValue')) && p() && e('1'); // 步骤7:验证第一行包含stageValue字段
r($caselibTest->getRowsForExportTemplateTest(2, $modules1)) && p('1:typeValue') && e('~~'); // 步骤8:验证第二行不包含typeValue字段