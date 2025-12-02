#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->linkStory();
timeout=0
cid=18006

- 测试releaseID为0时，关联需求 @0
- 测试releaseID为1时，关联需求
 - 第0条的old属性 @1
 - 第0条的new属性 @1,2,3
- 测试releaseID不存在时，关联需求 @0
- 测试releaseID=0，需求为空时，关联需求 @0
- 测试releaseID=1，需求为空时，关联需求
 - 第0条的old属性 @1,2,3
 - 第0条的new属性 @1,2,3,
- 测试releaseID不存在，需求为空时，关联需求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$release = zenData('release')->loadYaml('release');
$release->stories->range('1-5');
$release->gen(5);

zenData('story')->loadYaml('story')->gen(5);
zenData('storystage')->gen(0);
zenData('user')->gen(5);
su('admin');

$releaseID  = array(0, 1, 6);
$stories[0] = array(2, 3);
$stories[1] = array();

$releaseTester = new releaseTest();
r($releaseTester->linkStoryTest($releaseID[0], $stories[0])) && p()                   && e('0');            // 测试releaseID为0时，关联需求
r($releaseTester->linkStoryTest($releaseID[1], $stories[0])) && p('0:old;0:new', ';') && e('1;1,2,3');      // 测试releaseID为1时，关联需求
r($releaseTester->linkStoryTest($releaseID[2], $stories[0])) && p()                   && e('0');            // 测试releaseID不存在时，关联需求
r($releaseTester->linkStoryTest($releaseID[0], $stories[1])) && p()                   && e('0');            // 测试releaseID=0，需求为空时，关联需求
r($releaseTester->linkStoryTest($releaseID[1], $stories[1])) && p('0:old;0:new', ';') && e('1,2,3;1,2,3,'); // 测试releaseID=1，需求为空时，关联需求
r($releaseTester->linkStoryTest($releaseID[2], $stories[1])) && p()                   && e('0');            // 测试releaseID不存在，需求为空时，关联需求