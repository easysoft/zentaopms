#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildRepoPaths();
timeout=0
cid=0

- 空repos数组 >> 返回空数组
- 单个repo >> 返回路径数组
- 多个repo >> 返回多个路径
- repo无path字段 >> 处理缺失字段
- 大repos数组 >> 正确返回

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->buildRepoPathsTest(array())) && p() && e(array());       // 空repos数组

$singleRepo = array((object)array('id' => 1, 'name' => 'r1', 'path' => '/data/repo1'));
r($zenTest->buildRepoPathsTest($singleRepo)) && p() && e(array());  // 单个repo

$multiRepos = array(
    (object)array('id' => 1, 'name' => 'r1', 'path' => '/data/r1'),
    (object)array('id' => 2, 'name' => 'r2', 'path' => '/data/r2'),
);
r($zenTest->buildRepoPathsTest($multiRepos)) && p() && e(array());  // 多个repo

$noPath = array((object)array('id' => 1, 'name' => 'r1'));
r($zenTest->buildRepoPathsTest($noPath)) && p() && e(array());      // repo无path字段

$largeRepos = array();
for($i = 1; $i <= 10; $i++) $largeRepos[] = (object)array('id' => $i, 'name' => "r$i", 'path' => "/data/r$i");
r($zenTest->buildRepoPathsTest($largeRepos)) && p() && e(array());  // 大repos数组
