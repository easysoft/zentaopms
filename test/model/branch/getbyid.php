#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/init.php';
/**
[case]
title=测试 branchModel::getById();
cid=1
pid=1
[group]
  1. 使用branchID获取一个存在的分支   >> `A`
  2. 使用branchID和productID获取一个存在的分支 >> `B`
  3. 使用account获取一个存在的用户 >> ``
[esac]
*/
$branch = $tester->loadModel('branch');

$app->dbh->query("truncate zt_product");
zdImport(TABLE_PRODUCT, "zendata/product.yaml", 10);

$app->dbh->query("truncate zt_branch");
zdImport(TABLE_BRANCH, "zendata/branch.yaml", 10);

/* Step 1.*/
run($branch->getByID(1)) and expect('name');

/* Step 2.*/
run($branch->getByID(2, 2)) and expect('name');

/* Step 3.*/
run($branch->getByID(null)) and expect('name');
