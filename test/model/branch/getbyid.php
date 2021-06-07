#!/usr/bin/env php
<?php
/**
title=测试 branchModel::getById();
cid=1
pid=1

使用branchID获取一个存在的分支   >> A
使用branchID和productID获取一个存在的分支 >> B
使用branchID获取一个不存在的分支 >>
*/

include dirname(dirname(__DIR__)) . '/init.php';

$branch = $tester->loadModel('branch');

$app->dbh->query("truncate zt_product");
$app->dbh->query("truncate zt_branch");

zdImport(TABLE_PRODUCT, "zendata/product.yaml", 10);
zdImport(TABLE_BRANCH, "zendata/branch.yaml", 10);

/* Group 1. */
run($branch->getByID(1))    and expect('name'); // Step 1.1.
run($branch->getByID(2, 2)) and expect('name'); // Step 1.2.
run($branch->getByID(null)) and expect('name'); // Step 1.3.
