#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');
zenData('bug')->loadYaml('fetchbuginfo_bug')->gen(5);
zenData('project')->loadYaml('execution')->gen(5);
zenData('story')->gen(5);
zenData('task')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);



/**

title=测试 bugTao::fetchBugInfo;
timeout=0
cid=15414

- 获取ID等于2的bug
 - 属性title @第1个bug
 - 属性executionName @项目集1
 - 属性storyTitle @用户需求1
 - 属性storyStatus @draft
 - 属性latestStoryVersion @3
 - 属性taskName @开发任务11
 - 属性planName @计划1

- 获取ID等于2的bug
 - 属性title @第2个bug
 - 属性executionName @项目集2
 - 属性storyTitle @软件需求2
 - 属性storyStatus @active
 - 属性latestStoryVersion @3
 - 属性taskName @开发任务12
 - 属性planName @计划2

- 获取ID等于2的bug
 - 属性title @第3个bug
 - 属性executionName @项目集3
 - 属性storyTitle @用户需求3
 - 属性storyStatus @closed
 - 属性latestStoryVersion @3
 - 属性taskName @开发任务13
 - 属性planName @计划3

- 获取ID等于2的bug
 - 属性title @第4个bug
 - 属性executionName @项目集4
 - 属性storyTitle @软件需求4
 - 属性storyStatus @changing
 - 属性latestStoryVersion @3
 - 属性taskName @开发任务14
 - 属性planName @计划4

- 获取ID等于2的bug
 - 属性title @0
 - 属性executionName @0
 - 属性storyTitle @0
 - 属性storyStatus @0
 - 属性latestStoryVersion @0
 - 属性taskName @0
 - 属性planName @0

*/

global $tester;
$tester->loadModel('bug');

$bugIdList = array(1, 2, 3, 4, 10001);

r($tester->bug->fetchBugInfo($bugIdList[0])) && p('title,executionName,storyTitle,storyStatus,latestStoryVersion,taskName,planName')  && e('第1个bug,项目集1,用户需求1,draft,3,开发任务11,计划1');    // 获取ID等于1的bug
r($tester->bug->fetchBugInfo($bugIdList[1])) && p('title,executionName,storyTitle,storyStatus,latestStoryVersion,taskName,planName')  && e('第2个bug,项目集2,软件需求2,active,3,开发任务12,计划2');   // 获取ID等于2的bug
r($tester->bug->fetchBugInfo($bugIdList[2])) && p('title,executionName,storyTitle,storyStatus,latestStoryVersion,taskName,planName')  && e('第3个bug,项目集3,用户需求3,closed,3,开发任务13,计划3');   // 获取ID等于3的bug
r($tester->bug->fetchBugInfo($bugIdList[3])) && p('title,executionName,storyTitle,storyStatus,latestStoryVersion,taskName,planName')  && e('第4个bug,项目集4,软件需求4,changing,3,开发任务14,计划4'); // 获取ID等于4的bug
r($tester->bug->fetchBugInfo($bugIdList[4])) && p('title,executionName,storyTitle,storyStatus,latestStoryVersion,taskName,planName')  && e('0,0,0,0,0,0,0');                                          // 获取ID不存在的bug
