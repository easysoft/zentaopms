#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByIdList();
timeout=0
cid=18049

- 执行repoTest模块的getByIdListTest方法，参数是array  @4
- 执行repoTest模块的getByIdListTest方法，参数是array 第1条的name属性 @repo1
- 执行repoTest模块的getByIdListTest方法，参数是array  @0
- 执行repoTest模块的getByIdListTest方法，参数是array  @0
- 执行repoTest模块的getByIdListTest方法，参数是array  @2
- 执行repoTest模块的getByIdListTest方法，参数是array 第1条的id属性 @1
- 执行repoTest模块的getByIdListTest方法，参数是array  @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh;
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$table = zenData('ops_repo');
$table->id->range('1-8');
$table->spaceID->range('1{8}');
$table->product->range('1{4},2{4}');
$table->name->range('repo1,repo2,repo3,repo4,repo5,repo6,repo7,repo8');
$table->SCM->range('Git{2},Gitlab{2},SVN{2},Subversion{2}');
$table->encrypt->range('base64{4},plain{4}');
$table->password->range('dGVzdA==,cGFzc3dvcmQ=,plaintext1,plaintext2,dGVzdDE=,cGFzc3dvcmQx,plaintext3,plaintext4');
$table->acl->range('open{8}');
$table->status->range('active{8}');
$table->deleted->range('0{6},1{2}');
$table->gen(8);

su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：正常获取多个存在的代码库ID
r(count($repoTest->getByIdListTest(array(1, 2, 3, 4)))) && p() && e('4');

// 测试步骤2：获取单个存在的代码库ID
r($repoTest->getByIdListTest(array(1))) && p('1:name') && e('repo1');

// 测试步骤3：获取不存在的代码库ID
r(count($repoTest->getByIdListTest(array(999, 1000)))) && p() && e('0');

// 测试步骤4：获取空ID列表
r(count($repoTest->getByIdListTest(array()))) && p() && e('0');

// 测试步骤5：获取混合存在和不存在的ID
r(count($repoTest->getByIdListTest(array(1, 2, 999, 1000)))) && p() && e('2');

// 测试步骤6：验证返回数据结构完整性
r($repoTest->getByIdListTest(array(1))) && p('1:id') && e('1');

// 测试步骤7：验证deleted字段过滤功能（只返回deleted=0的记录）
r(count($repoTest->getByIdListTest(array(1, 2, 3, 4, 5, 6, 7, 8)))) && p() && e('6');
