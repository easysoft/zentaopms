#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::checkChildColumn();
timeout=0
cid=16877

- 步骤1：正常子列验证通过 @1
- 步骤2：子列名称为空验证失败 @0
- 步骤3：子列限制为负数验证失败 @0
- 步骤4：子列限制非整数验证失败 @0
- 步骤5：父列限制冲突验证失败 @0
- 步骤6：父列无限制时子列有限制验证通过 @1

*/
// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 准备测试数据
$table = zenData('kanbancolumn');
$table->id->range('1-20');
$table->region->range('1-5');
$table->group->range('1-3');
$table->name->range('待处理,进行中,已完成,测试中,已归档');
$table->limit->range('0-10');
$table->parent->range('0-5');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$kanbanTest = new kanbanModelTest();

// 5. 准备测试场景数据
// 父列有限制（限制为5）
$parentColumn = new stdclass();
$parentColumn->name = '父列测试';
$parentColumn->limit = 5;

// 父列无限制
$unlimitedParent = new stdclass();
$unlimitedParent->name = '无限制父列';
$unlimitedParent->limit = -1;

// 正常子列
$validChild = new stdclass();
$validChild->name = '有效子列';
$validChild->limit = 3;
$validChild->noLimit = 0;

// 名称为空的子列
$emptyNameChild = new stdclass();
$emptyNameChild->name = '';
$emptyNameChild->limit = 2;
$emptyNameChild->noLimit = 0;

// 负数限制的子列
$negativeChild = new stdclass();
$negativeChild->name = '负数限制子列';
$negativeChild->limit = -2;
$negativeChild->noLimit = 0;

// 非整数限制的子列
$invalidChild = new stdclass();
$invalidChild->name = '无效限制子列';
$invalidChild->limit = 'abc';
$invalidChild->noLimit = 0;

// 超出父列限制的子列
$exceedChild = new stdclass();
$exceedChild->name = '超限子列';
$exceedChild->limit = 8;
$exceedChild->noLimit = 0;

// 父列无限制时的有限制子列
$childWithParentUnlimited = new stdclass();
$childWithParentUnlimited->name = '正常子列';
$childWithParentUnlimited->limit = 5;
$childWithParentUnlimited->noLimit = '0';

// 6. 执行测试步骤（确保至少5个）
r($kanbanTest->checkChildColumnTest($parentColumn, $validChild, 2)) && p() && e('1'); // 步骤1：正常子列验证通过
r($kanbanTest->checkChildColumnTest($parentColumn, $emptyNameChild, 2)) && p() && e('0'); // 步骤2：子列名称为空验证失败
r($kanbanTest->checkChildColumnTest($unlimitedParent, $negativeChild, 2)) && p() && e('0'); // 步骤3：子列限制为负数验证失败
r($kanbanTest->checkChildColumnTest($parentColumn, $invalidChild, 2)) && p() && e('0'); // 步骤4：子列限制非整数验证失败
r($kanbanTest->checkChildColumnTest($parentColumn, $exceedChild, 6)) && p() && e('0'); // 步骤5：父列限制冲突验证失败
r($kanbanTest->checkChildColumnTest($unlimitedParent, $childWithParentUnlimited, 3)) && p() && e('1'); // 步骤6：父列无限制时子列有限制验证通过