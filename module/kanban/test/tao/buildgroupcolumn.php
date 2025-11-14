#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildGroupColumn();
timeout=0
cid=16971

- 执行$result1属性id @1
- 执行$result2属性cards @3
- 执行$result3属性parentName @1
- 执行$result4属性group @task
- 执行$result5属性type @story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试新建列数据结构（columnList为空时）
$columnList = array();
$column = (object)array(
    'column' => 1,
    'columnType' => 'story',
    'columnName' => '待处理',
    'color' => 'red',
    'limit' => 0,
    'parent' => 0,
    'lane' => 'admin'
);
$laneData = array(
    'id' => 'admin',
    'region' => 1
);
$browseType = 'story';
$result1 = $kanbanTest->buildGroupColumnTest($columnList, $column, $laneData, $browseType);
r($result1) && p('id') && e('1');

// 步骤2：测试已存在列数据结构（columnList已包含该列时）
$existingColumnList = array(
    1 => array(
        'id' => 1,
        'type' => 'story',
        'name' => 1,
        'title' => '待处理',
        'color' => 'blue',
        'limit' => 5,
        'region' => 1,
        'laneName' => 'admin',
        'group' => 'story',
        'cards' => 3,
        'actionList' => array('setColumn', 'setWIP')
    )
);
$result2 = $kanbanTest->buildGroupColumnTest($existingColumnList, $column, $laneData, $browseType);
r($result2) && p('cards') && e('3');

// 步骤3：测试包含父列的列数据结构
$columnWithParent = (object)array(
    'column' => 2,
    'columnType' => 'story',
    'columnName' => '开发中',
    'color' => 'blue',
    'limit' => 5,
    'parent' => 1,
    'lane' => 'user1'
);
$result3 = $kanbanTest->buildGroupColumnTest(array(), $columnWithParent, $laneData, $browseType);
r($result3) && p('parentName') && e('1');

// 步骤4：测试不同browseType的列数据结构
$taskBrowseType = 'task';
$result4 = $kanbanTest->buildGroupColumnTest(array(), $column, $laneData, $taskBrowseType);
r($result4) && p('group') && e('task');

// 步骤5：测试列的基本字段映射和数据完整性
$result5 = $kanbanTest->buildGroupColumnTest(array(), $column, $laneData, $browseType);
r($result5) && p('type') && e('story');