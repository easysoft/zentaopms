#!/usr/bin/env php
<?php

/**

title=测试 productZen::getExportFields();
timeout=0
cid=0

- 步骤1:测试返回的字段数组包含name字段且值为产品名称属性name @产品名称
- 步骤2:测试返回的字段数组包含productLine字段且值为所属产品线属性productLine @所属产品线
- 步骤3:测试返回的字段数组包含PO字段且值为负责人属性PO @负责人
- 步骤4:测试返回的字段包含draftStories字段且包含草稿关键词属性draftStories @ - 草稿
- 步骤5:测试返回的字段数据类型是数组 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getExportFieldsTest()) && p('name') && e('产品名称'); // 步骤1:测试返回的字段数组包含name字段且值为产品名称
r($productTest->getExportFieldsTest()) && p('productLine') && e('所属产品线'); // 步骤2:测试返回的字段数组包含productLine字段且值为所属产品线
r($productTest->getExportFieldsTest()) && p('PO') && e('负责人'); // 步骤3:测试返回的字段数组包含PO字段且值为负责人
r($productTest->getExportFieldsTest()) && p('draftStories') && e(' - 草稿'); // 步骤4:测试返回的字段包含draftStories字段且包含草稿关键词
r(gettype($productTest->getExportFieldsTest())) && p() && e('array'); // 步骤5:测试返回的字段数据类型是数组