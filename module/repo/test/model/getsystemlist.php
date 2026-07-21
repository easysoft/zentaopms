#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getSystemList();
timeout=0
cid=0

- 空查询条件 @0
- 带space参数 @0
- 无效space @0
- space=0 @0
- 第二次空查询 @0

*/

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getSystemListTest()) && p() && e('0');       // 空查询条件
r($repoTest->getSystemListTest('', 1)) && p() && e('0');  // 带space参数
r($repoTest->getSystemListTest('', 999)) && p() && e('0'); // 无效space
r($repoTest->getSystemListTest('', 0)) && p() && e('0');  // space=0
r($repoTest->getSystemListTest()) && p() && e('0');       // 第二次空查询