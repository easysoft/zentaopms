#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getStoryReleases();
timeout=0
cid=18002

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$release = zenData('release')->loadYaml('release');
$release->stories->range('`1,2,3`, `1,2`, `3,4`, `2,3`, []');
$release->gen(5);

zenData('user')->gen(5);
su('admin');

$stories = array(0, 1, 2, 3, 6);

global $tester;
$tester->loadModel('release');
r($tester->release->getStoryReleases($stories[0])) && p()         && e('0');     // 测试获取关联需求ID=0的发布列表
r($tester->release->getStoryReleases($stories[1])) && p('1:name') && e('发布1'); // 测试获取关联需求ID=1的发布列表
r($tester->release->getStoryReleases($stories[2])) && p('2:name') && e('发布2'); // 测试获取关联需求ID=2的发布列表
r($tester->release->getStoryReleases($stories[3])) && p('3:name') && e('发布3'); // 测试获取关联需求ID=3的发布列表
r($tester->release->getStoryReleases($stories[4])) && p()         && e('0');     // 测试获取关联需求ID不存在的发布列表

r(count($tester->release->getStoryReleases($stories[0]))) && p() && e('0'); // 测试获取关联需求ID=0的发布数量
r(count($tester->release->getStoryReleases($stories[1]))) && p() && e('2'); // 测试获取关联需求ID=1的发布数量
r(count($tester->release->getStoryReleases($stories[2]))) && p() && e('3'); // 测试获取关联需求ID=2的发布数量
r(count($tester->release->getStoryReleases($stories[3]))) && p() && e('3'); // 测试获取关联需求ID=3的发布数量
r(count($tester->release->getStoryReleases($stories[4]))) && p() && e('0'); // 测试获取关联需求ID不存在的发布数量
