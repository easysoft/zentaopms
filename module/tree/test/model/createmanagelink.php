#!/usr/bin/env php
<?php

/**

title=测试 treeModel::createManageLink();
timeout=0
cid=19352

- 执行treeTest模块的createManageLinkTest方法，参数是'story', $module1
 - 属性id @1
 - 属性parent @0
 - 属性name @Story模块
- 执行treeTest模块的createManageLinkTest方法，参数是'bug', $module2 属性name @Bug模块[B][admin]
- 执行treeTest模块的createManageLinkTest方法，参数是'case', $module3 属性name @Case模块[C]
- 执行treeTest模块的createManageLinkTest方法，参数是'task', $module4
 - 属性id @4
 - 属性parent @0
 - 属性name @普通模块
- 执行treeTest模块的createManageLinkTest方法，参数是'bug', $module5 属性name @Bug无负责人[B]

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('module')->loadYaml('module_createmanagelink', false, 2)->gen(10);
zendata('user')->loadYaml('user_createmanagelink', false, 2)->gen(10);

su('admin');

$treeTest = new treeModelTest();

// 测试步骤1：创建story类型的管理链接
$module1 = new stdclass();
$module1->id = 1;
$module1->parent = 0;
$module1->name = 'Story模块';
$module1->owner = '';
r($treeTest->createManageLinkTest('story', $module1)) && p('id,parent,name') && e('1,0,Story模块');

// 测试步骤2：创建bug类型的管理链接（有负责人）
$module2 = new stdclass();
$module2->id = 2;
$module2->parent = 1;
$module2->name = 'Bug模块';
$module2->owner = 'admin';
r($treeTest->createManageLinkTest('bug', $module2)) && p('name') && e('Bug模块[B][admin]');

// 测试步骤3：创建case类型的管理链接
$module3 = new stdclass();
$module3->id = 3;
$module3->parent = 1;
$module3->name = 'Case模块';
$module3->owner = '';
r($treeTest->createManageLinkTest('case', $module3)) && p('name') && e('Case模块[C]');

// 测试步骤4：创建无效类型的管理链接
$module4 = new stdclass();
$module4->id = 4;
$module4->parent = 0;
$module4->name = '普通模块';
$module4->owner = '';
r($treeTest->createManageLinkTest('task', $module4)) && p('id,parent,name') && e('4,0,普通模块');

// 测试步骤5：测试bug类型但无负责人的管理链接
$module5 = new stdclass();
$module5->id = 5;
$module5->parent = 0;
$module5->name = 'Bug无负责人';
$module5->owner = '';
r($treeTest->createManageLinkTest('bug', $module5)) && p('name') && e('Bug无负责人[B]');