#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDataSource();
timeout=0
cid=15082

- 步骤1：返回数据源分组数量 @23
- 步骤2：program模块使用配置的字段 @name
- 步骤3：doc模块使用配置的字段 @title
- 步骤4：charter模块字段数量 @2
- 步骤5：story模块过滤deleted等系统字段 @0
- 步骤6：productplan.stories映射到story工作流字段 @title

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();
$result = $aiTest->getDataSourceTest();

r(count($result)) && p() && e('23'); // 返回数据源分组数量
r($result['program']['program'][0]) && p() && e('name'); // program模块使用配置的字段
r($result['doc']['doc'][0]) && p() && e('title'); // doc模块使用配置的字段
r(count($result['charter']['charter'])) && p() && e('2'); // charter模块字段数量
r(in_array('deleted', $result['story']['story']) ? 1 : 0) && p() && e('0'); // story模块过滤deleted等系统字段
r($result['productplan']['stories'][0]) && p() && e('title'); // productplan.stories映射到story工作流字段