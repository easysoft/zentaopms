#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForChange();
timeout=0
cid=0

- 步骤1：正常需求变更字段获取第title条的name属性 @title
- 步骤2：评审者字段配置第reviewer条的name属性 @reviewer
- 步骤3：编辑器字段控件类型第spec条的control属性 @editor
- 步骤4：标题字段默认值设置第title条的default属性 @软件需求4
- 步骤5：不存在需求处理属性error @story_not_found

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('story')->loadYaml('story_getformfieldsforchange', false, 2)->gen(10);
zendata('storyspec')->loadYaml('storyspec_getformfieldsforchange', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('测试产品1,测试产品2,测试产品3');
$productTable->PO->range('admin{3}');
$productTable->status->range('normal{3}');
$productTable->gen(3);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户一,用户二,用户三,用户四');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getFormFieldsForChangeTest(1)) && p('title:name') && e('title'); // 步骤1：正常需求变更字段获取
r($storyTest->getFormFieldsForChangeTest(2)) && p('reviewer:name') && e('reviewer'); // 步骤2：评审者字段配置
r($storyTest->getFormFieldsForChangeTest(3)) && p('spec:control') && e('editor'); // 步骤3：编辑器字段控件类型
r($storyTest->getFormFieldsForChangeTest(4)) && p('title:default') && e('软件需求4'); // 步骤4：标题字段默认值设置
r($storyTest->getFormFieldsForChangeTest(999)) && p('error') && e('story_not_found'); // 步骤5：不存在需求处理