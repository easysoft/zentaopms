#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::parsePrefixToArray();
timeout=0
cid=0

- 测试步骤1：解析单个前缀 @dev/
- 测试步骤2：解析多个前缀(逗号分隔) @feature/,feat/
- 测试步骤3：解析带空格的前缀(应自动trim) @dev/,test/
- 测试步骤4：解析空字符串 @0
- 测试步骤5：解析包含空项的前缀(应过滤空项) @dev/,test/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 测试步骤1：解析单个前缀
r($branchTypeTest->parsePrefixToArrayTest('dev/')) && p() && e('dev/');

// 测试步骤2：解析多个前缀(逗号分隔)
r($branchTypeTest->parsePrefixToArrayTest('feature/,feat/')) && p() && e('feature/,feat/');

// 测试步骤3：解析带空格的前缀(应自动trim)
r($branchTypeTest->parsePrefixToArrayTest(' dev/ , test/ ')) && p() && e('dev/,test/');

// 测试步骤4：解析空字符串
r($branchTypeTest->parsePrefixToArrayTest('')) && p() && e('0');

// 测试步骤5：解析包含空项的前缀(应过滤空项)
r($branchTypeTest->parsePrefixToArrayTest('dev/,,test/')) && p() && e('dev/,test/');
