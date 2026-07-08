#!/usr/bin/env php
<?php

/**

title=测试 treeModel::createEpicLink();
timeout=0
cid=19350

- 步骤1：正常情况
 - 属性id @1
 - 属性parent @0
 - 属性name @测试模块
- 步骤2：项目史诗链接属性url @projectstory-story-10-5--byModule-1-epic.html
- 步骤3：执行史诗链接属性url @execution-story-20-epic-order_desc-byModule-1.html
- 步骤4：产品史诗链接属性url @product-browse-1-all-byModule-1-epic.html
- 步骤5：子级链接属性parent @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$treeTest = new treeModelTest();

// 4. 创建测试模块对象
$module = new stdclass();
$module->id = 1;
$module->parent = 0;
$module->name = '测试模块';
$module->root = 1;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($treeTest->createEpicLinkTest('story', $module)) && p('id,parent,name') && e('1,0,测试模块'); // 步骤1：正常情况
r($treeTest->createEpicLinkTest('story', $module, '0', array('projectID' => 10, 'productID' => 5))) && p('url') && e('createepiclink.php?m=projectstory&f=story&projectID=10&productID=5&branch=&browseType=byModule&param=1&storyType=epic'); // 步骤2：项目史诗链接
r($treeTest->createEpicLinkTest('story', $module, '0', array('executionID' => 20))) && p('url') && e('createepiclink.php?m=execution&f=story&executionID=20&storyType=epic&orderBy=order_desc&type=byModule&param=1'); // 步骤3：执行史诗链接
r($treeTest->createEpicLinkTest('story', $module, '0', array('branchID' => 'all'))) && p('url') && e('createepiclink.php?m=product&f=browse&root=1&branch=all&type=byModule&param=1&storyType=epic'); // 步骤4：产品史诗链接
r($treeTest->createEpicLinkTest('story', $module, '5')) && p('parent') && e('5'); // 步骤5：子级链接
