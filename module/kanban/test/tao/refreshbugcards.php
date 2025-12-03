#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshBugCards();
timeout=0
cid=16989

- 步骤1:测试未确认Bug的卡片刷新
 - 属性unconfirmed @
- 步骤2:测试已确认Bug的卡片刷新
 - 属性confirmed @
- 步骤3:测试已解决Bug的卡片刷新
 - 属性fixed @
- 步骤4:测试已关闭Bug的卡片刷新
 - 属性closed @
- 步骤5:测试包含已有卡片的刷新
 - 属性unconfirmed @

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备(根据需要配置)
zendata('bug')->loadYaml('bug', false, 2)->gen(20);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求:必须包含至少5个测试步骤
$cardPairs = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'resolving' => '', 'testing' => '', 'tested' => '', 'closed' => '');
r($kanbanTest->refreshBugCardsTest($cardPairs, 101, '')) && p('unconfirmed') && e(',1,2,3,4,5,'); // 步骤1:测试未确认Bug的卡片刷新
r($kanbanTest->refreshBugCardsTest($cardPairs, 101, '')) && p('confirmed') && e(',6,7,8,9,10,'); // 步骤2:测试已确认Bug的卡片刷新
r($kanbanTest->refreshBugCardsTest($cardPairs, 101, '')) && p('fixed') && e(',11,12,13,14,15,'); // 步骤3:测试已解决Bug的卡片刷新
r($kanbanTest->refreshBugCardsTest($cardPairs, 101, '')) && p('closed') && e(',16,17,18,19,20,'); // 步骤4:测试已关闭Bug的卡片刷新
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => ',100,', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'resolving' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 101, '')) && p('unconfirmed') && e(',1,2,3,4,5,100,'); // 步骤5:测试包含已有卡片的刷新