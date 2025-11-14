#!/usr/bin/env php
<?php

/**

title=测试 treeModel::createSceneLink();
timeout=0
cid=19356

- 步骤1：正常情况验证id和name
 - 属性id @1
 - 属性name @测试场景模块1
- 步骤2：带分支ID验证id属性id @2
- 步骤3：带排序参数验证name属性name @测试场景模块1
- 步骤4：传入parent且模块无parent属性parent @100
- 步骤5：模块有parent优先使用自身parent属性parent @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$treeTest = new treeTest();

// 4. 准备测试数据对象
$module1 = new stdclass();
$module1->id = 1;
$module1->name = '测试场景模块1';
$module1->root = 10;
$module1->parent = '';

$module2 = new stdclass();
$module2->id = 2;
$module2->name = '测试场景模块2';
$module2->root = 20;
$module2->parent = '';

$module3 = new stdclass();
$module3->id = 3;
$module3->name = '测试场景模块3';
$module3->root = 30;
$module3->parent = 2;

$extra1 = array('branchID' => '5', 'orderBy' => 'id_asc');
$extra2 = array('branchID' => '', 'orderBy' => 'name_desc');

// 5. 强制要求：必须包含至少5个测试步骤
r($treeTest->createSceneLinkTest('scene', $module1, '', array())) && p('id,name') && e('1,测试场景模块1'); // 步骤1：正常情况验证id和name
r($treeTest->createSceneLinkTest('scene', $module2, '', $extra1)) && p('id') && e('2'); // 步骤2：带分支ID验证id
r($treeTest->createSceneLinkTest('scene', $module1, '', $extra2)) && p('name') && e('测试场景模块1'); // 步骤3：带排序参数验证name
r($treeTest->createSceneLinkTest('scene', $module1, '100', array())) && p('parent') && e('100'); // 步骤4：传入parent且模块无parent
r($treeTest->createSceneLinkTest('scene', $module3, '100', array())) && p('parent') && e('2'); // 步骤5：模块有parent优先使用自身parent