#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::updateColumnParent();
timeout=0
cid=16994

- 步骤1：删除父列1的唯一子列3后，父列1的parent重置为0属性result @1
- 步骤2：删除父列2的一个子列4，但父列2还有子列5，不重置属性result @0
- 步骤3：没有父列的情况，正常处理属性result @0
- 步骤4：删除父列2的一个子列5，父列2还有子列4，不重置属性result @0
- 步骤5：删除父列7的唯一子列8后，父列7的parent重置为0属性result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备 - 使用默认数据
zenData('kanbancolumn')->gen(50);

// 3. 用户登录（选择合适角色）
su('admin');

// 手动插入测试需要的特定数据
global $tester;
$tester->dao->delete()->from(TABLE_KANBANCOLUMN)->exec();

// 插入测试数据：父列和子列的关系
// 情况1：父列1有1个子列3，删除子列3后父列1应该被重置parent=0
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 1, 'parent' => 10, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '父列1', 'color' => 'blue', 'limit' => -1, 'order' => 1, 'archived' => '0', 'deleted' => '0'
))->exec();

// 情况2：父列2有2个子列4、5，删除其中一个后父列2不应该重置
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 2, 'parent' => 20, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '父列2', 'color' => 'red', 'limit' => -1, 'order' => 2, 'archived' => '0', 'deleted' => '0'
))->exec();

// 子列3：父列1的唯一子列
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 3, 'parent' => 1, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '子列1-1', 'color' => 'green', 'limit' => -1, 'order' => 3, 'archived' => '0', 'deleted' => '0'
))->exec();

// 子列4：父列2的第一个子列
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 4, 'parent' => 2, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '子列2-1', 'color' => 'blue', 'limit' => -1, 'order' => 4, 'archived' => '0', 'deleted' => '0'
))->exec();

// 子列5：父列2的第二个子列  
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 5, 'parent' => 2, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '子列2-2', 'color' => 'red', 'limit' => -1, 'order' => 5, 'archived' => '0', 'deleted' => '0'
))->exec();

// 独立列：没有父列
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 6, 'parent' => 0, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '无父列', 'color' => 'green', 'limit' => -1, 'order' => 6, 'archived' => '0', 'deleted' => '0'
))->exec();

// 父列3：有1个子列7，用于第二轮测试
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 7, 'parent' => 30, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '父列3', 'color' => 'yellow', 'limit' => -1, 'order' => 7, 'archived' => '0', 'deleted' => '0'
))->exec();

// 子列8：父列3的唯一子列
$tester->dao->insert(TABLE_KANBANCOLUMN)->data(array(
    'id' => 8, 'parent' => 7, 'type' => 'column', 'region' => 1, 'group' => 1, 'name' => '子列3-1', 'color' => 'purple', 'limit' => -1, 'order' => 8, 'archived' => '0', 'deleted' => '0'
))->exec();

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->updateColumnParentTest(3)) && p('result') && e('1'); // 步骤1：删除父列1的唯一子列3后，父列1的parent重置为0
r($kanbanTest->updateColumnParentTest(4)) && p('result') && e('0'); // 步骤2：删除父列2的一个子列4，但父列2还有子列5，不重置
r($kanbanTest->updateColumnParentTest(6)) && p('result') && e('0'); // 步骤3：没有父列的情况，正常处理
r($kanbanTest->updateColumnParentTest(5)) && p('result') && e('0'); // 步骤4：删除父列2的一个子列5，父列2还有子列4，不重置
r($kanbanTest->updateColumnParentTest(8)) && p('result') && e('1'); // 步骤5：删除父列7的唯一子列8后，父列7的parent重置为0