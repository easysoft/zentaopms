#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAssignMeBlockID();
timeout=0
cid=0

- 步骤1:isonlybody()为false时,返回0 @0
- 步骤2:没有符合条件的block记录,返回0 @0
- 步骤3:vision不匹配,返回0 @0
- 步骤4:module不是assigntome,返回0 @0
- 步骤5:用户账号不匹配,返回0 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
zendata('block')->gen(0); // 先清空数据

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$storyTest = new storyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤

// 步骤1:isonlybody()为false时,应该返回0
unset($_GET['onlybody']);
r($storyTest->getAssignMeBlockIDTest()) && p() && e('0'); // 步骤1:isonlybody()为false时,返回0

// 步骤2:isonlybody()为true,但没有符合条件的block记录时,应该返回0
$_GET['onlybody'] = 'yes';
r($storyTest->getAssignMeBlockIDTest()) && p() && e('0'); // 步骤2:没有符合条件的block记录,返回0

// 步骤3:isonlybody()为true,vision不匹配时,应该返回0
$table = zendata('block');
$table->id->range('1');
$table->account->range('admin');
$table->vision->range('lite');
$table->module->range('assigntome');
$table->title->range('指派给我的需求');
$table->gen(1);
r($storyTest->getAssignMeBlockIDTest()) && p() && e('0'); // 步骤3:vision不匹配,返回0

// 步骤4:isonlybody()为true,module不是assigntome时,应该返回0
zendata('block')->gen(0);
$table = zendata('block');
$table->id->range('1');
$table->account->range('admin');
$table->vision->range('rnd');
$table->module->range('my');
$table->title->range('我的待办');
$table->gen(1);
r($storyTest->getAssignMeBlockIDTest()) && p() && e('0'); // 步骤4:module不是assigntome,返回0

// 步骤5:isonlybody()为true,用户账号不匹配时,应该返回0
zendata('block')->gen(0);
$table = zendata('block');
$table->id->range('1');
$table->account->range('user1');
$table->vision->range('rnd');
$table->module->range('assigntome');
$table->title->range('指派给我的需求');
$table->gen(1);
r($storyTest->getAssignMeBlockIDTest()) && p() && e('0'); // 步骤5:用户账号不匹配,返回0