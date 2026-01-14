#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByID();
timeout=0
cid=18048

- 测试步骤1：正常获取存在的repo对象
 - 属性id @1
 - 属性name @testHtml
 - 属性SCM @Gitlab
- 测试步骤2：验证repo对象的基本属性属性serviceProject @1
- 测试步骤3：测试不存在的repoID @0
- 测试步骤4：测试无效的repoID(0) @0
- 测试步骤5：测试负数repoID @0
- 测试步骤6：验证Gitea仓库信息
 - 属性name @unittest
 - 属性SCM @Gitea
- 测试步骤7：验证SVN仓库加密信息
 - 属性account @admin
 - 属性encrypt @base64

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 使用现有的repo数据
zenData('repo')->loadYaml('repo')->gen(4);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

r($repoTest->getByIDTest(1)) && p('id,name,SCM') && e('1,testHtml,Gitlab'); // 测试步骤1：正常获取存在的repo对象
r($repoTest->getByIDTest(2)) && p('serviceProject') && e('1'); // 测试步骤2：验证repo对象的基本属性
r($repoTest->getByIDTest(999)) && p() && e('0'); // 测试步骤3：测试不存在的repoID
r($repoTest->getByIDTest(0)) && p() && e('0'); // 测试步骤4：测试无效的repoID(0)
r($repoTest->getByIDTest(-1)) && p() && e('0'); // 测试步骤5：测试负数repoID
r($repoTest->getByIDTest(3)) && p('name,SCM') && e('unittest,Gitea'); // 测试步骤6：验证Gitea仓库信息
r($repoTest->getByIDTest(4)) && p('account,encrypt') && e('admin,base64'); // 测试步骤7：验证SVN仓库加密信息