#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->getAllPairs();
timeout=0
cid=1

*/

$params = 'noempty';
$branch = new branchTest();

r($branch->getAllPairsTest())        && p() && e('11'); // 测试获取全部分支名
r($branch->getAllPairsTest($params)) && p() && e('10'); // 测试获取全部分支名
