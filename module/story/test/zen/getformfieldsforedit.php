#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForEdit();
timeout=0
cid=1

- 测试获取普通产品的软件需求编辑表单字段
 - 第product条的control属性 @select
 - 第product条的title属性 @所属产品
- 测试获取多分支产品的软件需求编辑表单字段
 - 第branch条的control属性 @select
 - 第branch条的title属性 @平台/分支
- 测试获取用户需求的编辑表单字段
 - 第assignedTo条的control属性 @select
 - 第assignedTo条的title属性 @指派给
- 测试获取业务需求的编辑表单字段
 - 第stage条的control属性 @select
 - 第stage条的title属性 @所处阶段
- 测试获取已关闭需求的编辑表单字段
 - 第status条的control属性 @hidden
 - 第status条的title属性 @当前状态
- 测试获取有父需求的子需求编辑表单字段
 - 第parent条的control属性 @select
 - 第parent条的title属性 @父需求

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('product')->loadYaml('product')->gen(3);
zenData('story')->loadYaml('story')->gen(20);
zenData('user')->gen(5);
zenData('productplan')->gen(5);
zenData('module')->gen(5);

su('admin');

$storyIDs = array(1, 11, 6, 16, 8, 2);

$storyTester = new storyZenTest();
r($storyTester->getFormFieldsForEditTest($storyIDs[0])) && p('product:control,title')    && e('select,所属产品');   // 测试获取普通产品的软件需求编辑表单字段
r($storyTester->getFormFieldsForEditTest($storyIDs[1])) && p('branch:control,title')     && e('select,平台/分支');  // 测试获取多分支产品的软件需求编辑表单字段
r($storyTester->getFormFieldsForEditTest($storyIDs[2])) && p('assignedTo:control,title') && e('select,指派给');     // 测试获取用户需求的编辑表单字段
r($storyTester->getFormFieldsForEditTest($storyIDs[3])) && p('stage:control,title')      && e('select,所处阶段');   // 测试获取业务需求的编辑表单字段
r($storyTester->getFormFieldsForEditTest($storyIDs[4])) && p('status:control,title')     && e('hidden,当前状态');   // 测试获取已关闭需求的编辑表单字段
r($storyTester->getFormFieldsForEditTest($storyIDs[5])) && p('parent:control,title')     && e('select,父需求');     // 测试获取有父需求的子需求编辑表单字段