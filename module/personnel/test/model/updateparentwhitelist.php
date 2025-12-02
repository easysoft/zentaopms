#!/usr/bin/env php
<?php

/**

title=测试 personnelModel::updateParentWhitelist();
timeout=0
cid=17339

- 步骤1：product类型更新父级program白名单 @parent_whitelist:user3,user1,user2;parent_acls:user3:sync,user1:sync,user2:sync;

- 步骤2：sprint类型更新父级project白名单 @parent_whitelist:user4,user5;parent_acls:user4:sync,user5:sync;

- 步骤3：非支持类型返回false @false
- 步骤4：不存在对象处理 @false
- 步骤5：替换模式处理被删除账户 @parent_whitelist:user3,user4;parent_acls:user3:sync,user4:sync;

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品{1-5}');
$product->program->range('11-15');
$product->whitelist->range('user1,user2,user3');
$product->gen(5);

$project = zenData('project');
$project->id->range('11-25');
$project->name->range('项目{11-25}');
$project->type->range('program{5},project{5},sprint{5}');
$project->project->range('0{5},0{5},16-20');
$project->whitelist->range('user1,user2');
$project->gen(15);

$acl = zenData('acl');
$acl->id->range('1-10');
$acl->account->range('user1{2},user2{2},user3{2},user4{2},user5{2}');
$acl->objectType->range('product{4},program{2},project{2},sprint{2}');
$acl->objectID->range('1,2,1,2,11,12,16,17,21,22');
$acl->type->range('whitelist{10}');
$acl->source->range('add{4},sync{6}');
$acl->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$personnelTest = new personnelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($personnelTest->updateParentWhitelistTest('product', 1, array('user1', 'user2', 'user3'), 'sync', 'replace', array(), TABLE_PRODUCT)) && p() && e('parent_whitelist:user3,user1,user2;parent_acls:user3:sync,user1:sync,user2:sync;'); // 步骤1：product类型更新父级program白名单
r($personnelTest->updateParentWhitelistTest('sprint', 21, array('user4', 'user5'), 'sync', 'replace', array(), TABLE_PROJECT)) && p() && e('parent_whitelist:user4,user5;parent_acls:user4:sync,user5:sync;'); // 步骤2：sprint类型更新父级project白名单
r($personnelTest->updateParentWhitelistTest('project', 16, array('user1', 'user2'), 'add', 'replace', array(), TABLE_PROJECT)) && p() && e('false'); // 步骤3：非支持类型返回false
r($personnelTest->updateParentWhitelistTest('product', 999, array('user1'), 'sync', 'replace', array(), TABLE_PRODUCT)) && p() && e('false'); // 步骤4：不存在对象处理
r($personnelTest->updateParentWhitelistTest('product', 2, array('user3', 'user4'), 'sync', 'replace', array('user1', 'user2'), TABLE_PRODUCT)) && p() && e('parent_whitelist:user3,user4;parent_acls:user3:sync,user4:sync;'); // 步骤5：替换模式处理被删除账户