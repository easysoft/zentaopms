#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('scene')->config('modulescene')->gen('170');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildMenuQuery();
cid=1
pid=1

*/

$productIdList = array(0, 1, 2, 41);
$moduleIdList  = array(0, 1821, 1825);
$sceneIdList   = array(0, 1, 6, 161);
$branchIdList  = array('all', '', '0');

$testcase = new testcaseTest();

r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[0]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 0 的查询语句
r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 0 分支 all 的查询语句
r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 0 分支 '' 的查询语句
r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 0 分支 0 的查询语句

r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[1]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `path`  LIKE '%' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 1 的查询语句
r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[1], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `path`  LIKE '%' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 1 分支 0 的查询语句

r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[2]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `path`  LIKE '%' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 6 的查询语句
r($testcase->buildMenuQueryTest($productIdList[0], $moduleIdList[0], $sceneIdList[2], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `path`  LIKE '%' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 0 模块 0 起始场景 6 分支 0 的查询语句


r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 0 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 0 分支 all 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 0 分支 '' 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 0 分支 0 的查询语句

r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[1]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `path`  LIKE '%' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 1 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[1], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `path`  LIKE '%' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 0 起始场景 1 分支 0 的查询语句

r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[1], $sceneIdList[1]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `module`  = '1821' AND  `path`  LIKE '%' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 1821 起始场景 1 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[1], $sceneIdList[1], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `module`  = '1821' AND  `path`  LIKE '%' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 1 模块 1821 起始场景 1 分支 0 的查询语句


r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 0 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 0 分支 all 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 0 分支 '' 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 0 分支 0 的查询语句

r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[1]))                   && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `path`  LIKE '%' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 161 的查询语句
r($testcase->buildMenuQueryTest($productIdList[1], $moduleIdList[0], $sceneIdList[1], $branchIdList[2])) && p() && e("SELECT * FROM `zt_scene` WHERE `deleted`  = '0' AND  `product`  = '1' AND  `path`  LIKE '%' AND  `branch`  = '0' ORDER BY `grade` desc,`sort`"); // 测试获取产品 41 模块 0 起始场景 161 分支 0 的查询语句
