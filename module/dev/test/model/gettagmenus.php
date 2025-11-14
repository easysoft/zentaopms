#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTagMenus();
timeout=0
cid=16012

- 测试空模块参数情况 @0
- 测试有效模块菜单生成第calendar条的active属性 @1
- 测试execution模块的task方法高亮第task条的active属性 @1
- 测试user模块菜单结构第todo条的title属性 @待办
- 测试project模块高亮匹配第browse条的active属性 @1
- 测试bug模块高亮匹配功能第browse条的active属性 @1
- 测试product模块requirement菜单高亮第requirement条的active属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

global $tester;
$devTest = new devTest();

r($devTest->getTagMenusTest('', '', '')) && p() && e('0'); // 测试空模块参数情况
r($devTest->getTagMenusTest('my', 'my', 'todo')) && p('calendar:active') && e('1'); // 测试有效模块菜单生成
r($devTest->getTagMenusTest('execution', 'execution', 'task')) && p('task:active') && e('1'); // 测试execution模块的task方法高亮
r($devTest->getTagMenusTest('user', 'user', 'browse')) && p('todo:title') && e('待办'); // 测试user模块菜单结构
r($devTest->getTagMenusTest('project', 'project', 'browse')) && p('browse:active') && e('1'); // 测试project模块高亮匹配
r($devTest->getTagMenusTest('bug', 'bug', 'browse')) && p('browse:active') && e('1'); // 测试bug模块高亮匹配功能
r($devTest->getTagMenusTest('product', 'product', 'browse')) && p('requirement:active') && e('1'); // 测试product模块requirement菜单高亮