#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getSpecifiedBlockID();
timeout=0
cid=15233

- 步骤1：正常情况查找存在的区块 @1
- 步骤2：查找不存在的区块 @0
- 步骤3：传入空参数 @0
- 步骤4：部分参数为空 @0
- 步骤5：查找项目列表区块 @3
- 步骤6：用户1的区块 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 手动准备测试数据
global $tester;
$dao = $tester->dao;

// 清理现有数据
$dao->delete()->from(TABLE_BLOCK)->exec();

// 插入测试数据
$dao->insert(TABLE_BLOCK)->data(array(
    'id'        => 1,
    'account'   => 'admin',
    'dashboard' => 'my',
    'module'    => 'welcome',
    'code'      => 'welcome',
    'title'     => 'block1',
    'params'    => '{}'
))->exec();

$dao->insert(TABLE_BLOCK)->data(array(
    'id'        => 2,
    'account'   => 'admin',
    'dashboard' => 'my',
    'module'    => 'guide',
    'code'      => 'guide',
    'title'     => 'block2',
    'params'    => '{}'
))->exec();

$dao->insert(TABLE_BLOCK)->data(array(
    'id'        => 3,
    'account'   => 'admin',
    'dashboard' => 'my',
    'module'    => 'project',
    'code'      => 'list',
    'title'     => 'block3',
    'params'    => '{}'
))->exec();

$dao->insert(TABLE_BLOCK)->data(array(
    'id'        => 4,
    'account'   => 'user1',
    'dashboard' => 'my',
    'module'    => 'guide',
    'code'      => 'guide',
    'title'     => 'block4',
    'params'    => '{}'
))->exec();

$dao->insert(TABLE_BLOCK)->data(array(
    'id'        => 5,
    'account'   => 'user1',
    'dashboard' => 'qa',
    'module'    => 'bug',
    'code'      => 'list',
    'title'     => 'block5',
    'params'    => '{}'
))->exec();

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$blockTest = new blockModelTest();

// 5. 至少5个测试步骤
r($blockTest->getSpecifiedBlockIDTest('my', 'welcome', 'welcome')) && p('') && e('1'); // 步骤1：正常情况查找存在的区块
r($blockTest->getSpecifiedBlockIDTest('qa', 'bug', 'notexist')) && p('') && e('0'); // 步骤2：查找不存在的区块
r($blockTest->getSpecifiedBlockIDTest('', '', '')) && p('') && e('0'); // 步骤3：传入空参数
r($blockTest->getSpecifiedBlockIDTest('my', '', 'welcome')) && p('') && e('0'); // 步骤4：部分参数为空
r($blockTest->getSpecifiedBlockIDTest('my', 'project', 'list')) && p('') && e('3'); // 步骤5：查找项目列表区块

// 步骤6：验证不同用户权限
su('user1');
$blockTest2 = new blockModelTest(); // 重新创建实例以获取新的用户上下文
r($blockTest2->getSpecifiedBlockIDTest('my', 'guide', 'guide')) && p('') && e('4'); // 步骤6：用户1的区块