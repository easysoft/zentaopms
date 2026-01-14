#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildGroupKanban();
timeout=0
cid=16972

- 执行$result) && count($result) == 3 @1
- 执行$result2) && count($result2[0]) == 0 @1
- 执行$result3) && count($result3) == 3 @1
- 执行$result4) && count($result4) == 3 @1
- 执行$result5) && count($result5) == 3 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$kanbanTest = new kanbanTaoTest();

// 5. 测试步骤：每个测试用例必须包含至少5个测试步骤

// 步骤1：测试正常数据构建看板分组视图
$lanes = array(
    'admin' => (object)array('id' => 'admin', 'name' => '管理员', 'execution' => 1, 'color' => 'red', 'order' => 1),
    'user1' => (object)array('id' => 'user1', 'name' => '用户1', 'execution' => 1, 'color' => 'blue', 'order' => 2)
);
$columns = array(
    (object)array('column' => 1, 'columnName' => '待处理', 'columnType' => 'story', 'color' => 'red', 'limit' => 0, 'parent' => 0, 'cards' => '1,2,3', 'lane' => 'admin'),
    (object)array('column' => 2, 'columnName' => '开发中', 'columnType' => 'story', 'color' => 'blue', 'limit' => 5, 'parent' => 0, 'cards' => '4,5', 'lane' => 'user1')
);
$cardGroup = array();
$menus = array();
$result = $kanbanTest->buildGroupKanbanTest($lanes, $columns, $cardGroup, '', 'assignedTo', 'story', $menus);
r(is_array($result) && count($result) == 3) && p() && e('1');

// 步骤2：测试空泳道数据
$result2 = $kanbanTest->buildGroupKanbanTest(array(), $columns, $cardGroup, '', 'assignedTo', 'story', $menus);
r(is_array($result2) && count($result2[0]) == 0) && p() && e('1');

// 步骤3：测试包含搜索值的情况
$result3 = $kanbanTest->buildGroupKanbanTest($lanes, $columns, $cardGroup, '需求', 'assignedTo', 'story', $menus);
r(is_array($result3) && count($result3) == 3) && p() && e('1');

// 步骤4：测试不同分组方式（按优先级分组）
$lanes2 = array(
    1 => (object)array('id' => 1, 'name' => '高', 'execution' => 1, 'color' => 'red', 'order' => 1),
    2 => (object)array('id' => 2, 'name' => '中', 'execution' => 1, 'color' => 'blue', 'order' => 2)
);
$result4 = $kanbanTest->buildGroupKanbanTest($lanes2, $columns, $cardGroup, '', 'pri', 'story', $menus);
r(is_array($result4) && count($result4) == 3) && p() && e('1');

// 步骤5：测试不同浏览类型（任务类型）
$result5 = $kanbanTest->buildGroupKanbanTest($lanes, $columns, $cardGroup, '', 'assignedTo', 'task', $menus);
r(is_array($result5) && count($result5) == 3) && p() && e('1');