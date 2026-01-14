#!/usr/bin/env php
<?php

/**

title=测试 storyModel::checkNeedConfirm();
timeout=0
cid=18482

- 执行checkNeedConfirmTest($data1)模块的needconfirm方法  @0
- 执行checkNeedConfirmTest($data2)模块的needconfirm方法  @1
- 执行needconfirm . ',' . (int)$result[2]模块的needconfirm方法  @0,1

- 执行checkNeedConfirmTest($data4)模块的needconfirm方法  @0
- 执行checkNeedConfirmTest($data5)模块的needconfirm方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5');
$storyTable->status->range('active{3},closed,draft');
$storyTable->version->range('2,3,2,1,1');
$storyTable->gen(5);

// 模拟管理员登录
su('admin');

// 创建测试实例
$storyTest = new storyModelTest();

// 测试步骤1：单个对象，story版本与数据库版本相同，期望needconfirm为false
$data1 = new stdclass();
$data1->id = 1;
$data1->story = 1;
$data1->storyVersion = 2;
r($storyTest->checkNeedConfirmTest($data1)->needconfirm) && p() && e('0');

// 测试步骤2：单个对象，story版本小于数据库版本，期望needconfirm为true
$data2 = new stdclass();
$data2->id = 2;
$data2->story = 2;
$data2->storyVersion = 1;
r($storyTest->checkNeedConfirmTest($data2)->needconfirm) && p() && e('1');

// 测试步骤3：对象数组，包含多个对象的混合场景
$dataArray = array();
$dataArray[1] = new stdclass();
$dataArray[1]->id = 1;
$dataArray[1]->story = 1;
$dataArray[1]->storyVersion = 2;
$dataArray[2] = new stdclass();
$dataArray[2]->id = 2;
$dataArray[2]->story = 2;
$dataArray[2]->storyVersion = 1;
$result = $storyTest->checkNeedConfirmTest($dataArray);
r((int)$result[1]->needconfirm . ',' . (int)$result[2]->needconfirm) && p() && e('0,1');

// 测试步骤4：对象没有story字段的情况，期望needconfirm为false
$data4 = new stdclass();
$data4->id = 4;
$data4->story = 0;
$data4->storyVersion = 1;
r($storyTest->checkNeedConfirmTest($data4)->needconfirm) && p() && e('0');

// 测试步骤5：story状态非active的情况，期望needconfirm为false
$data5 = new stdclass();
$data5->id = 5;
$data5->story = 4;
$data5->storyVersion = 1;
r($storyTest->checkNeedConfirmTest($data5)->needconfirm) && p() && e('0');