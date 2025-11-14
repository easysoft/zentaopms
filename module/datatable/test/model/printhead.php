#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::printHead();
timeout=0
cid=15946

- 步骤1：正常列配置 @1
- 步骤2：隐藏列配置 @0
- 步骤3：actions列配置 @1
- 步骤4：id列带复选框 @1
- 步骤5：不可排序列 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/datatable.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$datatableTest = new datatableTest();

// 4. 测试步骤数据准备
// 正常列配置
$normalCol = new stdClass();
$normalCol->id = 'title';
$normalCol->show = true;
$normalCol->width = '200px';
$normalCol->title = '标题';
$normalCol->fixed = 'no';

// 隐藏列配置
$hiddenCol = new stdClass();
$hiddenCol->id = 'hidden';
$hiddenCol->show = false;
$hiddenCol->width = '100px';
$hiddenCol->title = '隐藏列';

// actions列配置
$actionsCol = new stdClass();
$actionsCol->id = 'actions';
$actionsCol->show = true;
$actionsCol->width = '120px';
$actionsCol->title = '操作';

// id列配置（带复选框）
$idCol = new stdClass();
$idCol->id = 'id';
$idCol->show = true;
$idCol->width = '90px';
$idCol->title = 'ID';
$idCol->sort = 'yes';

// 不可排序列配置
$noSortCol = new stdClass();
$noSortCol->id = 'status';
$noSortCol->show = true;
$noSortCol->width = '100px';
$noSortCol->title = '状态';
$noSortCol->sort = 'no';

// 5. 强制要求：必须包含至少5个测试步骤
r($datatableTest->printHeadTest(array('cols' => $normalCol, 'orderBy' => 'id_desc', 'vars' => 'productID=1', 'checkBox' => true))) && p() && e('1'); // 步骤1：正常列配置
r($datatableTest->printHeadTest(array('cols' => $hiddenCol, 'orderBy' => '', 'vars' => '', 'checkBox' => false))) && p() && e('0'); // 步骤2：隐藏列配置
r($datatableTest->printHeadTest(array('cols' => $actionsCol, 'orderBy' => '', 'vars' => '', 'checkBox' => false))) && p() && e('1'); // 步骤3：actions列配置
r($datatableTest->printHeadTest(array('cols' => $idCol, 'orderBy' => 'id_desc', 'vars' => 'productID=1', 'checkBox' => true))) && p() && e('1'); // 步骤4：id列带复选框
r($datatableTest->printHeadTest(array('cols' => $noSortCol, 'orderBy' => 'id_desc', 'vars' => 'productID=1', 'checkBox' => true))) && p() && e('1'); // 步骤5：不可排序列