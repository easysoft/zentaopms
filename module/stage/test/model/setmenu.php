#!/usr/bin/env php
<?php
/**

title=测试 stageModel->setMenu();
cid=18423

- 测试无效模块的阶段菜单配置 @0
- 测试阶段类型设置页的菜单配置 @stage|browse|,stage,stage|browse|
- 测试阶段列表页的菜单配置 @stage|browse|,stage,stage|browse|
- 测试批量创建阶段页的菜单配置 @stage|browse|,stage,stage|browse|
- 测试已删除页面方法的菜单配置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$modules = array('story', 'stage');
$methods = array('settype', 'browse', 'batchcreate', 'browseplus');

$stageTester = new stageModelTest();
r($stageTester->setMenuType($modules[0], $methods[1])) && p() && e('0');                                      // 测试无效模块的阶段菜单配置
r($stageTester->setMenuType($modules[1], $methods[0])) && p() && e('stage|browse|,stage,stage|browse|');    // 测试阶段类型设置页的菜单配置
r($stageTester->setMenuType($modules[1], $methods[1])) && p() && e('stage|browse|,stage,stage|browse|');    // 测试阶段列表页的菜单配置
r($stageTester->setMenuType($modules[1], $methods[2])) && p() && e('stage|browse|,stage,stage|browse|');    // 测试批量创建阶段页的菜单配置
r($stageTester->setMenuType($modules[1], $methods[3])) && p() && e('0');                                      // 测试已删除页面方法的菜单配置
