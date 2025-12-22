#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranchTypeList();
timeout=0
cid=0

- 测试步骤1：获取repoID=1的所有分支类型(应返回2条) @2
- 测试步骤2：按名称搜索"开发分支" 统计数量 @1
- 测试步骤3：按key搜索"feature" 验证key属性 @feature
- 测试步骤4：按prefix搜索"dev/" 验证第一个prefix @dev/
- 测试步骤5：不传repoID获取所有分支类型(应返回3条) @3
- 测试步骤6：验证prefixes字段被正确解析为数组 @array
- 测试步骤7：验证返回的对象包含所有必要字段 属性name,key @开发分支,feature

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 加载测试数据
zenData('branch_type')->loadYaml('branchtype')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoTest();

// 测试步骤1：获取repoID=1的所有分支类型(应返回2条)
r($repoTest->getBranchTypeListTest(1)) && p() && e('2'); 

// 测试步骤2：按名称搜索"开发分支" 统计数量
r($repoTest->getBranchTypeListTest(1, '开发分支')) && p() && e('1'); 

// 测试步骤3：按key搜索"feature" 验证key属性
r($repoTest->getBranchTypeListTest(1, '', 'feature')) && p('1:key') && e('feature'); 

// 测试步骤4：按prefix搜索"dev/" 验证第一个prefix
r($repoTest->getBranchTypeListTest(1, '', '', 'dev/')) && p('1:prefixes[0]') && e('dev/'); 

// 测试步骤5：不传repoID获取所有分支类型(应返回3条)
r($repoTest->getBranchTypeListTest(0)) && p() && e('3'); 

// 测试步骤6：验证prefixes字段被正确解析为数组
r($repoTest->getBranchTypeListTest(1)) && p('1:prefixes') && e('array'); 

// 测试步骤7：验证返回的对象包含所有必要字段 属性name,key
r($repoTest->getBranchTypeListTest(1)) && p('1:name,key') && e('开发分支,feature'); 
