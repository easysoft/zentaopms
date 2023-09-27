#!/usr/bin/env php
<?php
/**

title=测试 buildModel->replaceNameWithRelease();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(10);
zdTable('project')->config('execution')->gen(30);
zdTable('branch')->config('branch')->gen(5);
zdTable('release')->config('release')->gen(10);
zdTable('user')->gen(5);
su('admin');

$branchIdList = array('all', '0', '1');
$paramsList   = array('', 'separate', 'noterminate', 'withbranch', 'releasetag', 'noreleased');

$buildTester = new buildTest();
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[0])) && p('2030-01-01:0') && e('发布10');        // 替换版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[1])) && p('2030-01-01:0') && e('发布10');        // 替换包含主干版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[2])) && p('2030-01-01:0') && e('发布10');        // 替换非停止维护版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[3])) && p('2030-01-01:0') && e('主干/发布10');   // 替换包含分支版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[4])) && p('2030-01-01:0') && e('发布10 [发布]'); // 替换包含发布标签版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[5])) && p('2030-01-01:0') && e('发布10');        // 替换包含没有关联发布版本的名称
