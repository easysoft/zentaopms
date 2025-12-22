#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranchTypeByID();
timeout=0
cid=0

- 测试步骤1：正常获取存在的分支类型对象
 - 属性id @1
 - 属性name @开发分支
 - 属性key @feature
- 测试步骤2：验证prefixes字段被正确解析 @array
- 测试步骤3：验证prefixes内容 @dev/
- 测试步骤4：测试不存在的typeID @0
- 测试步骤5：测试无效的typeID(0) @0
- 测试步骤6：验证第二个分支类型信息
 - 属性name @修复分支
 - 属性key @hotfix

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 加载测试数据
zenData('branch_type')->loadYaml('branchtype')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoTest();

// 测试步骤1：正常获取存在的分支类型对象
r($repoTest->getBranchTypeByIDTest(1)) && p('id,name,key') && e('1,开发分支,feature'); 

// 测试步骤2：验证prefixes字段被正确解析
r($repoTest->getBranchTypeByIDTest(1)) && p('prefixes') && e('array');

// 测试步骤3：验证prefixes内容
r($repoTest->getBranchTypeByIDTest(1)) && p('prefixes[0]') && e('dev/');

// 测试步骤4：测试不存在的typeID
r($repoTest->getBranchTypeByIDTest(999)) && p() && e('0');

// 测试步骤5：测试无效的typeID(0)
r($repoTest->getBranchTypeByIDTest(0)) && p() && e('0');

// 测试步骤6：验证第二个分支类型信息
r($repoTest->getBranchTypeByIDTest(2)) && p('name,key') && e('修复分支,hotfix');
