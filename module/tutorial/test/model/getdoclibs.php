#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDocLibs();
timeout=0
cid=19425

- 测试获取文档库列表的基本信息
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Doc Lib
 - 第2条的type属性 @custom
 - 第2条的vision属性 @rnd
 - 第2条的addedBy属性 @admin
- 测试文档库2的详细属性
 - 第2条的parent属性 @1
 - 第2条的product属性 @0
 - 第2条的project属性 @0
 - 第2条的execution属性 @0
- 测试文档库2的权限和状态属性
 - 第2条的acl属性 @open
 - 第2条的main属性 @0
 - 第2条的deleted属性 @0
- 测试文档库2的排序和统计属性
 - 第2条的order属性 @0
 - 第2条的allCount属性 @2
- 测试文档库2的其他空属性
 - 第2条的baseUrl属性 @~~
 - 第2条的collector属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDocLibsTest()) && p('2:id,name,type,vision,addedBy') && e('2,Test Doc Lib,custom,rnd,admin'); // 测试获取文档库列表的基本信息
r($tutorialTest->getDocLibsTest()) && p('2:parent,product,project,execution') && e('1,0,0,0'); // 测试文档库2的详细属性
r($tutorialTest->getDocLibsTest()) && p('2:acl,main,deleted') && e('open,0,0'); // 测试文档库2的权限和状态属性
r($tutorialTest->getDocLibsTest()) && p('2:order,allCount') && e('0,2'); // 测试文档库2的排序和统计属性
r($tutorialTest->getDocLibsTest()) && p('2:baseUrl,collector') && e('~~,~~'); // 测试文档库2的其他空属性