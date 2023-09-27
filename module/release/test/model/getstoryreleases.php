#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';
su('admin');

$build = zdTable('release');
$build->stories->range('1-20{2}');
$build->createdBy->range('admin');
$build->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$build->gen(20);

/**

title=测试 buildModel->getStoryBuilds();
cid=1
pid=1

项目版本查询 >> 项目版本版本7
执行版本查询 >> 执行版本版本17
无id查询 >> 0
图片字段传字符串测试 >> 17

*/
global $tester;
$release = $tester->loadModel('release');
r(count($release->getStoryReleases(1))) && p() && e('2');  //项目版本查询
