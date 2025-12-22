#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranchTypeByKey();
timeout=0
cid=0

- 测试步骤1：根据repoID和key获取分支类型
 - 属性id @1
 - 属性name @开发分支
 - 属性key @feature
- 测试步骤2：验证prefixes字段正确解析 @array
- 测试步骤3：测试不存在的key @0
- 测试步骤4：测试另一个仓库的分支类型
 - 属性name @发布分支
 - 属性key @release
- 测试步骤5：测试repoID不匹配的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 加载测试数据
zenData('branch_type')->loadYaml('branchtype')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoTest();

// 测试步骤1：根据repoID和key获取分支类型
r($repoTest->getBranchTypeByKeyTest(1, 'feature')) && p('id,name,key') && e('1,开发分支,feature'); 

// 测试步骤2：验证prefixes字段正确解析
r($repoTest->getBranchTypeByKeyTest(1, 'feature')) && p('prefixes') && e('array');

// 测试步骤3：测试不存在的key
r($repoTest->getBranchTypeByKeyTest(1, 'notexist')) && p() && e('0');

// 测试步骤4：测试另一个仓库的分支类型
r($repoTest->getBranchTypeByKeyTest(2, 'release')) && p('name,key') && e('发布分支,release'); 

// 测试步骤5：测试repoID不匹配的情况
r($repoTest->getBranchTypeByKeyTest(999, 'feature')) && p() && e('0'); 
