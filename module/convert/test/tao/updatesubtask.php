#!/usr/bin/env php
<?php

/**

title=测试 convertTao::updateSubTask();
timeout=0
cid=15876

- 步骤1：正常父子任务关系 @1
- 步骤2：空任务链接数组 @1
- 步骤3：issue列表中缺少父任务数据 @1
- 步骤4：issue类型非ztask @1
- 步骤5：复杂多层父子任务关系 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->name->range('父任务1,子任务1,子任务2,父任务2,子任务3,任务6,任务7,任务8,任务9,任务10');
$table->parent->range('0{10}');
$table->isParent->range('0{10}');
$table->path->range('1{10}');
$table->execution->range('1-5,1-5');
$table->status->range('wait{5},doing{3},done{2}');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->updateSubTaskTest(array('issue1' => array('issue2', 'issue3')), array('issue1' => array('BType' => 'ztask', 'BID' => '1'), 'issue2' => array('BType' => 'ztask', 'BID' => '2'), 'issue3' => array('BType' => 'ztask', 'BID' => '3')))) && p() && e(1); // 步骤1：正常父子任务关系
r($convertTest->updateSubTaskTest(array(), array())) && p() && e(1); // 步骤2：空任务链接数组
r($convertTest->updateSubTaskTest(array('issue1' => array('issue2')), array('issue2' => array('BType' => 'ztask', 'BID' => '2')))) && p() && e(1); // 步骤3：issue列表中缺少父任务数据
r($convertTest->updateSubTaskTest(array('issue1' => array('issue2')), array('issue1' => array('BType' => 'story', 'BID' => '1'), 'issue2' => array('BType' => 'ztask', 'BID' => '2')))) && p() && e(1); // 步骤4：issue类型非ztask
r($convertTest->updateSubTaskTest(array('issue1' => array('issue2', 'issue3'), 'issue4' => array('issue5')), array('issue1' => array('BType' => 'ztask', 'BID' => '4'), 'issue2' => array('BType' => 'ztask', 'BID' => '5'), 'issue3' => array('BType' => 'ztask', 'BID' => '6'), 'issue4' => array('BType' => 'ztask', 'BID' => '7'), 'issue5' => array('BType' => 'ztask', 'BID' => '8')))) && p() && e(1); // 步骤5：复杂多层父子任务关系