#!/usr/bin/env php
<?php

/**

title=测试 taskZen::processFilterTitle();
timeout=0
cid=18939

- 步骤1：普通状态过滤 @状态: 未开始
- 步骤2：按产品过滤 @状态: 产品A
- 步骤3：其他有效状态测试 @状态: 进行中
- 步骤4：从statusSelects获取状态名称 @状态: 已完成
- 步骤5：无效状态测试（返回原始browseType） @状态: invalid

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

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
$lang->task->report = new stdclass();
$lang->task->report->tpl = new stdclass();
$lang->task->report->tpl->feature = '状态: %s';
$lang->task->report->tpl->search = '%s %s %s';
$lang->task->report->tpl->multi = '(%s) %s (%s)';

$lang->execution->featureBar['task']['wait'] = '未开始';
$lang->execution->featureBar['task']['doing'] = '进行中';
$lang->execution->statusSelects['done'] = '已完成';

$lang->all = '全部';

$lang->search->andor = new stdclass();
$lang->search->andor->and = ' 并且 ';
$lang->search->andor->or = ' 或者 ';

$lang->search->operators = new stdclass();
$lang->search->operators->equal = '=';
$lang->search->operators->include = '包含';

$config->execution->search['fields']['module'] = '模块';

// 设置搜索配置以避免错误
$_SESSION['taskForm'] = array();
global $app;
$app->session = new stdclass();
$app->session->taskForm = null;

// 5. 创建测试实例
$taskTest = new taskZenTest();

// 6. 强制要求：必须包含至少5个测试步骤
r($taskTest->processFilterTitleTest('wait', 0)) && p() && e('状态: 未开始'); // 步骤1：普通状态过滤
r($taskTest->processFilterTitleTest('byproduct', 1)) && p() && e('状态: 产品A'); // 步骤2：按产品过滤
r($taskTest->processFilterTitleTest('doing', 0)) && p() && e('状态: 进行中'); // 步骤3：其他有效状态测试
r($taskTest->processFilterTitleTest('done', 0)) && p() && e('状态: 已完成'); // 步骤4：从statusSelects获取状态名称
r($taskTest->processFilterTitleTest('invalid', 0)) && p() && e('状态: invalid'); // 步骤5：无效状态测试（返回原始browseType）