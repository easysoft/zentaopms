#!/usr/bin/env php
<?php

/**

title=测试 storyZen::processFilterTitle();
timeout=0
cid=18701

- 步骤1：普通状态过滤（未关闭） @状态: 未关闭
- 步骤2：按产品过滤 @状态: 测试产品A
- 步骤3：按模块过滤 @模块 = 模块1
- 步骤4：草稿状态过滤 @状态: 草稿
- 步骤5：评审中状态过滤 @状态: 评审中

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('测试产品A,测试产品B,测试产品C{5}');
$product->status->range('normal{10}');
$product->deleted->range('0{10}');
$product->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('模块1,模块2,模块3{5}');
$module->type->range('story{10}');
$module->deleted->range('0{10}');
$module->gen(10);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

// 3. 用户登录
su('admin');

// 4. 设置必要的语言变量和配置
global $lang, $config, $app;
$lang->story->report = new stdclass();
$lang->story->report->tpl = new stdclass();
$lang->story->report->tpl->feature = '状态: %s';
$lang->story->report->tpl->search = '%s %s %s';
$lang->story->report->tpl->multi = '(%s) %s (%s)';

$lang->execution->featureBar['story']['unclosed'] = '未关闭';
$lang->execution->featureBar['story']['draft'] = '草稿';
$lang->execution->featureBar['story']['reviewing'] = '评审中';
$lang->execution->featureBar['story']['all'] = '全部';

$lang->search->andor = new stdclass();
$lang->search->andor->and = ' 并且 ';
$lang->search->andor->or = ' 或者 ';

$lang->search->operators = new stdclass();
$lang->search->operators->equal = '=';
$lang->search->operators->include = '包含';

$config->execution->search['fields']['module'] = '模块';
$config->product->search['fields']['module'] = '模块';

// 设置搜索配置以避免错误
$_SESSION['executionStoryForm'] = null;
$_SESSION['executionStorysearchParams'] = array('params' => array('module' => array('values' => array(1 => '模块1', 2 => '模块2'))));
$_SESSION['projectstoryForm'] = null;
$_SESSION['projectstorysearchParams'] = array('params' => array('module' => array('values' => array(1 => '模块1', 2 => '模块2'))));
$_SESSION['multiple'] = false;

$app->session = new stdclass();
$app->session->executionStoryForm = null;
$app->session->executionStorysearchParams = array('params' => array('module' => array('values' => array(1 => '模块1', 2 => '模块2'))));
$app->session->projectstoryForm = null;
$app->session->projectstorysearchParams = array('params' => array('module' => array('values' => array(1 => '模块1', 2 => '模块2'))));
$app->session->multiple = false;

// 5. 创建测试实例
$storyTest = new storyZenTest();

// 6. 强制要求：必须包含至少5个测试步骤
r($storyTest->processFilterTitleTest('unclosed', 0)) && p() && e('状态: 未关闭'); // 步骤1：普通状态过滤（未关闭）
r($storyTest->processFilterTitleTest('byproduct', 1)) && p() && e('状态: 测试产品A'); // 步骤2：按产品过滤
r($storyTest->processFilterTitleTest('bymodule', 1)) && p() && e('模块 = 模块1'); // 步骤3：按模块过滤
r($storyTest->processFilterTitleTest('draft', 0)) && p() && e('状态: 草稿'); // 步骤4：草稿状态过滤
r($storyTest->processFilterTitleTest('reviewing', 0)) && p() && e('状态: 评审中'); // 步骤5：评审中状态过滤