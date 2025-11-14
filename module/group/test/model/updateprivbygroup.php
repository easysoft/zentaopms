#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updatePrivByGroup();
timeout=0
cid=16724

- 更新my的权限，检查my模块的权限是否更新正确属性my @index|work
- 更新my的权限，检查其他nav的权限是否有影响属性module1 @method1
- 更新所有nav的权限，检查权限是否已更新属性my @index|work
- 更新所有nav的权限，检查其他nav的权限是否已删除属性module1 @~~
- 更新所有nav的权限，检查其他nav的权限是否已删除属性module1 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

/*
 * 生成的权限如下：
 * group 1: module1-method1, module6-method6
 * group 2: module2-method2, module7-method7
 */
zenData('group')->loadYaml('group')->gen(5);
zenData('grouppriv')->loadYaml('grouppriv')->gen(10);

global $app;
$app->rawModule = 'group';
$app->rawMethod = 'updatePrivByGroup';

$group = new groupTest();
$actions = array('my' => array('index', 'work'));

r($group->updatePrivByGroupTest(1, 'my', '', $actions)) && p('my')      && e('index|work');  //更新my的权限，检查my模块的权限是否更新正确
r($group->updatePrivByGroupTest(1, 'my', '', $actions)) && p('module1') && e('method1');     //更新my的权限，检查其他nav的权限是否有影响
r($group->updatePrivByGroupTest(2, '',   '', $actions)) && p('my')      && e('index|work');  //更新所有nav的权限，检查权限是否已更新
r($group->updatePrivByGroupTest(2, '',   '', $actions)) && p('module1') && e('~~');          //更新所有nav的权限，检查其他nav的权限是否已删除
r($group->updatePrivByGroupTest(3, '',   '', $actions)) && p('module1') && e('~~');          //更新所有nav的权限，检查其他nav的权限是否已删除
