#!/usr/bin/env php
<?php

/**

title=测试 cronModel::checkChange();
timeout=0
cid=15878

- 步骤1：存在lastTime为NULL且status不为stop的定时任务 @1
- 步骤2：所有定时任务都有lastTime值 @0
- 步骤3：存在lastTime为NULL但status为stop的定时任务 @0
- 步骤4：混合状态测试 @0
- 步骤5：空数据库测试 @0
- 步骤6：多个lastTime为NULL且status不为stop的定时任务 @1
- 步骤7：边界状态测试 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 1. 数据准备
$table = zenData('cron');
$table->id->range('1-10');
$table->m->range('0-59');
$table->h->range('0-23');
$table->dom->range('1-31');
$table->mon->range('1-12');
$table->dow->range('0-6');
$table->command->range('echo \"test\",php test.php,/bin/bash script.sh');
$table->remark->range('测试任务1,测试任务2,系统任务');
$table->type->range('zentao,system');
$table->buildin->range('0,1');
$table->status->range('normal{3},stop{2},running{1}');
$table->gen(6);

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$cronTest = new cronModelTest();

// 设置部分记录的lastTime为NULL且status不为stop
$cronTest->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(NULL)->set('status')->eq('normal')->where('id')->in('1,2,3')->exec();

// 4. 测试步骤执行
r($cronTest->checkChangeTest()) && p() && e('1'); // 步骤1：存在lastTime为NULL且status不为stop的定时任务

// 更新所有定时任务的lastTime
$cronTest->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(helper::now())->exec();
r($cronTest->checkChangeTest()) && p() && e('0'); // 步骤2：所有定时任务都有lastTime值

// 重置数据：设置一个lastTime为NULL但status为stop的定时任务
$cronTest->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(NULL)->set('status')->eq('stop')->where('id')->eq(1)->exec();
r($cronTest->checkChangeTest()) && p() && e('0'); // 步骤3：存在lastTime为NULL但status为stop的定时任务

// 混合状态测试：部分有lastTime，部分为NULL但status为stop
$cronTest->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(helper::now())->exec();
$cronTest->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(NULL)->set('status')->eq('stop')->where('id')->in('1,2')->exec();
r($cronTest->checkChangeTest()) && p() && e('0'); // 步骤4：混合状态测试

// 清空所有数据
$cronTest->objectModel->dao->delete()->from(TABLE_CRON)->exec();
r($cronTest->checkChangeTest()) && p() && e('0'); // 步骤5：空数据库测试

// 重新插入多个lastTime为NULL且status不为stop的定时任务
$cronTest->objectModel->dao->insert(TABLE_CRON)->data(array(
    'id' => 1,
    'm' => '0',
    'h' => '1',
    'dom' => '*',
    'mon' => '*',
    'dow' => '*',
    'command' => 'echo \"test1\"',
    'remark' => '测试任务1',
    'type' => 'zentao',
    'buildin' => 0,
    'status' => 'normal',
    'lastTime' => NULL
))->exec();
$cronTest->objectModel->dao->insert(TABLE_CRON)->data(array(
    'id' => 2,
    'm' => '30',
    'h' => '2',
    'dom' => '*',
    'mon' => '*',
    'dow' => '*',
    'command' => 'echo \"test2\"',
    'remark' => '测试任务2',
    'type' => 'system',
    'buildin' => 0,
    'status' => 'normal',
    'lastTime' => NULL
))->exec();
r($cronTest->checkChangeTest()) && p() && e('1'); // 步骤6：多个lastTime为NULL且status不为stop的定时任务

// 边界状态测试：status为normal但lastTime为NULL
$cronTest->objectModel->dao->delete()->from(TABLE_CRON)->exec();
$cronTest->objectModel->dao->insert(TABLE_CRON)->data(array(
    'id' => 1,
    'm' => '15',
    'h' => '3',
    'dom' => '*',
    'mon' => '*',
    'dow' => '*',
    'command' => 'php /path/to/script.php',
    'remark' => '边界测试任务',
    'type' => 'zentao',
    'buildin' => 1,
    'status' => 'normal',
    'lastTime' => NULL
))->exec();
r($cronTest->checkChangeTest()) && p() && e('1'); // 步骤7：边界状态测试