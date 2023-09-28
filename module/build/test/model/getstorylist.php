#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getBugList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('story')->config('story')->gen(10);
zdTable('storystage')->gen(0);
zdTable('user')->gen(5);
su('admin');

$storyIdList = array('', '1,2,3,4,5', '11,12,13,14,15');

global $tester;
$tester->loadModel('build');
r($tester->build->getStoryList($storyIdList[0])) && p()          && e('0');     // 测试传入空数组获取story列表数据
r($tester->build->getStoryList($storyIdList[1])) && p('1:title') && e('需求1'); // 测试传入storyId列表获取story列表数据
r($tester->build->getStoryList($storyIdList[2])) && p()          && e('0');     // 测试传入不存在storyId列表获取story列表数据
