#!/usr/bin/env php
<?php

/**

title=测试 productModel::buildSearchConfig();
timeout=0
cid=17474

- 测试步骤1:正常产品story类型第fields条的title属性 @需求名称
- 测试步骤2:正常产品requirement类型第fields条的stage属性 @所处阶段
- 测试步骤3:正常产品story类型包含module第fields条的module属性 @所属模块
- 测试步骤4:正常产品story类型包含stage第fields条的stage属性 @所处阶段
- 测试步骤5:fields不包含product字段第fields条的product属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('story')->loadYaml('story', false, 2)->gen(20);
zenData('storyspec')->loadYaml('storyspec', false, 2)->gen(20);
zenData('module')->loadYaml('module', false, 2)->gen(10);
zenData('productplan')->loadYaml('productplan', false, 2)->gen(10);
zenData('branch')->loadYaml('branch', false, 2)->gen(10);

su('admin');

global $config, $lang;
$lang->ERCommon = '业务需求';
$lang->URCommon = '用户需求';
$lang->SRCommon = '研发需求';
$config->product->search['fields']['stage']  = '所处阶段';
$config->product->search['fields']['module'] = '所属模块';

$productTest = new productModelTest();

r($productTest->buildSearchConfigTest(1, 'story')) && p('fields:title') && e('需求名称'); // 测试步骤1:正常产品story类型
r($productTest->buildSearchConfigTest(1, 'requirement')) && p('fields:stage') && e('所处阶段'); // 测试步骤2:正常产品requirement类型
r($productTest->buildSearchConfigTest(1, 'story')) && p('fields:module') && e('所属模块'); // 测试步骤3:正常产品story类型包含module
r($productTest->buildSearchConfigTest(1, 'story')) && p('fields:stage') && e('所处阶段'); // 测试步骤4:正常产品story类型包含stage
r($productTest->buildSearchConfigTest(1, 'story')) && p('fields:product') && e('~~'); // 测试步骤5:fields不包含product字段