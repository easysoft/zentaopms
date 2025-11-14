#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableFieldsMenu();
timeout=0
cid=15186

- 测试正常调用返回数组类型 @array
- 测试返回空数组的情况 @not_empty
- 测试菜单结构完整性验证 @valid
- 测试表名和字段类型格式 @valid
- 测试菜单层级结构验证 @valid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$bi = new biTest();

r($bi->getTableFieldsMenuTest()) && p() && e('array');                          // 测试正常调用返回数组类型
r($bi->getTableFieldsMenuTestEmpty()) && p() && e('not_empty');                 // 测试返回空数组的情况
r($bi->getTableFieldsMenuTestStructure()) && p() && e('valid');                 // 测试菜单结构完整性验证
r($bi->getTableFieldsMenuTestFormat()) && p() && e('valid');                    // 测试表名和字段类型格式
r($bi->getTableFieldsMenuTestHierarchy()) && p() && e('valid');                 // 测试菜单层级结构验证