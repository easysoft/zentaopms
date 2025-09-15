#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getTableFieldsMenu();
timeout=0
cid=0

- 测试正常调用返回数组类型 @array
- 测试返回结果包含表字段菜单结构属性~ @array
- 测试菜单项包含key属性第0条的key属性 @~~
- 测试菜单项包含text属性第0条的text属性 @~~
- 测试菜单项包含items属性第0条的items属性 @array

*/

$bi = new biTest();

r($bi->getTableFieldsMenuTest()) && p() && e('array');                          // 测试正常调用返回数组类型
r($bi->getTableFieldsMenuTest()) && p('~') && e('array');                       // 测试返回结果包含表字段菜单结构
r($bi->getTableFieldsMenuTest()) && p('0:key') && e('~~');                      // 测试菜单项包含key属性
r($bi->getTableFieldsMenuTest()) && p('0:text') && e('~~');                     // 测试菜单项包含text属性
r($bi->getTableFieldsMenuTest()) && p('0:items') && e('array');                 // 测试菜单项包含items属性