#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getProductsByRepo();
timeout=0
cid=18073

- 步骤1：无效代码库ID(0) @0
- 步骤2：不存在的代码库ID(99) @0
- 步骤3：单个产品关联(product=1)属性1 @正常产品1
- 步骤4：多个产品关联(product=2)属性2 @正常产品2
- 步骤5：过滤已删除产品(product=4)属性4 @正常产品4
- 步骤6：产品字段为空(product='') @0
- 步骤7：关联不存在产品(product=10) @0
- 步骤8：关联已删除产品(product=9，已删除) @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$productTable = zenData('product');
$productTable->id->range('1-12');
$productTable->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,正常产品6,正常产品7,正常产品8,已删除产品1,已删除产品2,不存在产品1,不存在产品2');
$productTable->code->range('product1,product2,product3,product4,product5,product6,product7,product8,deleted1,deleted2,notexist1,notexist2');
$productTable->deleted->range('0,0,0,0,0,0,0,0,1,1,0,0');
$productTable->gen(12);

$repoProducts = array(1 => '1', 2 => '2', 3 => '3', 4 => '1', 5 => '2', 6 => '2', 7 => '3', 8 => '4', 9 => '5', 10 => '', 11 => '10', 12 => '99', 13 => '9');
$repoNames    = array(
    1  => 'singleProductRepo',
    2  => 'multiProductRepo1',
    3  => 'multiProductRepo2',
    4  => 'testRepo1',
    5  => 'testRepo2',
    6  => 'mixedRepo1',
    7  => 'mixedRepo2',
    8  => 'withDeletedProductRepo',
    9  => 'validRepo',
    10 => 'emptyProductRepo',
    11 => 'invalidProductRepo1',
    12 => 'invalidProductRepo2',
    13 => 'deletedProductRepo',
);

foreach($repoProducts as $repoID => $product)
{
    $tester->dao->insert(TABLE_REPO)->data((object)array(
        'id'      => $repoID,
        'spaceID' => 1,
        'product' => $product,
        'name'    => $repoNames[$repoID],
        'path'    => "http://repo.local/repo{$repoID}",
        'SCM'     => 'Gitlab',
        'gitUID'  => "uid{$repoID}",
        'acl'     => 'private',
        'status'  => 'active',
        'deleted' => 0,
    ))->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();
}
su('admin');

$repo = new repoModelTest();
$repo->seedGitFoxEntry();

r($repo->getProductsByRepoTest(0)) && p() && e('0');                    // 步骤1：无效代码库ID(0)
r($repo->getProductsByRepoTest(99)) && p() && e('0');                   // 步骤2：不存在的代码库ID(99)
r($repo->getProductsByRepoTest(1)) && p('1') && e('正常产品1');          // 步骤3：单个产品关联(product=1)
r($repo->getProductsByRepoTest(5)) && p('2') && e('正常产品2');          // 步骤4：多个产品关联(product=2)
r($repo->getProductsByRepoTest(8)) && p('4') && e('正常产品4');          // 步骤5：过滤已删除产品(product=4)
r($repo->getProductsByRepoTest(10)) && p() && e('0');                   // 步骤6：产品字段为空(product='')
r($repo->getProductsByRepoTest(11)) && p() && e('0');                   // 步骤7：关联不存在产品(product=10)
r($repo->getProductsByRepoTest(13)) && p() && e('0');                   // 步骤8：关联已删除产品(product=9，已删除)
