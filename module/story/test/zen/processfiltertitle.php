#!/usr/bin/env php
<?php
/**
title=测试 storyZen::processFilterTitle();
timeout=0
cid=1
*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A,产品B,产品C{5}');
$product->status->range('normal{10}');
$product->deleted->range('0{10}');
$product->gen(10);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

// 3. 用户登录
su('admin');

// 4. 设置必要的语言变量
global $lang, $config, $app;
$lang->story->report = new stdclass();
$lang->story->report->tpl = new stdclass();
$lang->story->report->tpl->feature = '状态: %s';
$lang->story->report->tpl->search  = '%s %s %s';
$lang->story->report->tpl->multi   = '(%s) %s (%s)';

$lang->story->statusList['draft']    = '草稿';
$lang->story->statusList['active']   = '激活';
$lang->story->statusList['changing'] = '变更中';
$lang->story->statusList['closed']   = '已关闭';

$lang->projectstory->featureBar['story']['allstory']  = '全部';
$lang->projectstory->featureBar['story']['unclosed']  = '未关闭';
$lang->projectstory->featureBar['story']['draft']     = '草稿';
$lang->projectstory->featureBar['story']['reviewing'] = '评审中';
$lang->projectstory->featureBar['story']['changing']  = '变更中';

$lang->all = '全部';
$lang->search->andor = new stdclass();
$lang->search->andor->and = ' 并且 ';
$lang->search->andor->or  = ' 或者 ';
$lang->search->operators  = new stdclass();
$lang->search->operators->equal   = '=';
$lang->search->operators->include = '包含';
$config->product->search['fields']['module'] = '模块';

$app->tab = 'project';

// 设置搜索配置以避免错误
$_SESSION['storyForm'] = array();
$app->session = new stdclass();
$app->session->storyForm = null;

// 5. 创建测试实例
$storyTest = new storyZenTest();

// 6. 执行测试步骤（必须≥5个，注释编号修正）
r($storyTest->processFilterTitleTest('draft', 0))     && p() && e('状态: 草稿');   // 步骤1：普通状态过滤
r($storyTest->processFilterTitleTest('byproduct', 1)) && p() && e('状态: 产品A');  // 步骤2：按产品过滤（修正期望值）
r($storyTest->processFilterTitleTest('reviewing', 0)) && p() && e('状态: 评审中'); // 步骤3：其他有效状态测试
r($storyTest->processFilterTitleTest('unclosed', 0))  && p() && e('状态: 未关闭'); // 步骤4：未关闭状态
r($storyTest->processFilterTitleTest('all', 0))       && p() && e('状态: 全部');   // 步骤5：全部状态
