#!/usr/bin/env php
<?php

/**

title=测试 testcaseTao::saveScene();
timeout=0
cid=19052

- 步骤1：新建场景正常情况属性result @success
- 步骤2：更新现有场景属性result @success
- 步骤3：更新不存在场景属性result @fail
- 步骤4：测试父场景设置属性result @success
- 步骤5：场景路径调整功能属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('scene');
$table->id->range('1-10');
$table->product->range('1-5');
$table->branch->range('0,1-3');
$table->module->range('0,1821,1822');
$table->title->range('现有场景1,现有场景2,现有场景3');
$table->parent->range('0,1,2');
$table->grade->range('1-3');
$table->path->range(',1,,2,,1,2,');
$table->openedBy->range('admin,user1');
$table->openedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`');
$table->lastEditedBy->range('admin,user1');
$table->lastEditedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`');
$table->deleted->range('0');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->saveSceneTest(array('name' => '新场景', 'product' => '1', 'branch' => '0', 'module' => '1821', 'tmpPId' => ''), array())) && p('result') && e('success'); // 步骤1：新建场景正常情况
r($testcaseTest->saveSceneTest(array('id' => '1', 'name' => '更新场景', 'product' => '1', 'branch' => '0', 'module' => '1821', 'tmpPId' => ''), array())) && p('result') && e('success'); // 步骤2：更新现有场景
r($testcaseTest->saveSceneTest(array('id' => '999', 'name' => '更新不存在场景', 'product' => '1', 'branch' => '0', 'module' => '1821', 'tmpPId' => ''), array())) && p('result') && e('fail'); // 步骤3：更新不存在场景
r($testcaseTest->saveSceneTest(array('name' => 'test', 'product' => '1', 'branch' => '0', 'module' => '1821', 'tmpPId' => '1'), array('1' => array('id' => '1', 'parent' => '0', 'grade' => '1', 'path' => ',1,')))) && p('result') && e('success'); // 步骤4：测试父场景设置
r($testcaseTest->saveSceneTest(array('name' => '子场景', 'product' => '1', 'branch' => '0', 'module' => '1821', 'tmpPId' => '1'), array('1' => array('id' => '1', 'parent' => '0', 'grade' => '1', 'path' => ',1,')))) && p('result') && e('success'); // 步骤5：场景路径调整功能