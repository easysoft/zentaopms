#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableFieldsMenu();
timeout=0
cid=15186

- 测试正常调用返回数组类型 @1
- 测试返回空数组的情况 @1
- 测试菜单结构完整性验证 @1
- 测试表名和字段类型格式 @1
- 测试菜单层级结构验证 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$bi   = new biModelTest();
$menu = $bi->getTableFieldsMenuTest();

r(is_array($menu)) && p() && e('1'); // 测试正常调用返回数组类型
r(!empty($menu)) && p() && e('1'); // 测试返回空数组的情况
r(isset($menu[0]['key']) && isset($menu[0]['text']) && isset($menu[0]['items'])) && p() && e('1'); // 测试菜单结构完整性验证
r(strpos($menu[0]['text'], '(table)') !== false && !empty($menu[0]['items'])) && p() && e('1'); // 测试表名和字段类型格式
r(isset($menu[0]['items'][0]['key']) && strpos($menu[0]['items'][0]['text'], '(') !== false) && p() && e('1'); // 测试菜单层级结构验证
