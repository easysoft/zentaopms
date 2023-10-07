#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getStoryBuilds();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';
su('admin');

$build = zdTable('build');
$build->builds->range('``');
$build->stories->range('1-20{2}');
$build->createdBy->range('admin');
$build->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$build->gen(20);

global $tester;
$build = $tester->loadModel('build');
r(count($build->getStoryBuilds(1)))  && p() && e('2');  // 查询需求ID为1的版本列表
r(count($build->getStoryBuilds(0)))  && p() && e('0');  // 查询需求ID为空的版本列表
r(count($build->getStoryBuilds(11))) && p() && e('0');  // 查询需求ID为11的版本列表
