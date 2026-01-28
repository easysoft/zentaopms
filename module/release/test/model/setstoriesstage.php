#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试 releaseModel->setStoriesStage();
timeout=0
cid=18013

- 测试草稿需求属性stage @released
- 测试激活需求属性stage @released
- 测试已关闭需求属性stage @closed
- 测试变更中需求属性stage @released
- 测试设计中需求属性stage @released

*/

$release = zenData('release')->loadYaml('release');
$release->status->range('normal');
$release->stories->range('`,1,2,3,4,5`');
$release->gen(1);

zenData('story')->gen(5);

$releaseTester = new releaseModelTest();
r($releaseTester->setStoriesStageTest('wait', 1))      && p('stage') && e('released'); // 测试草稿需求
r($releaseTester->setStoriesStageTest('planned', 2))   && p('stage') && e('released'); // 测试激活需求
r($releaseTester->setStoriesStageTest('projected', 3)) && p('stage') && e('closed');   // 测试已关闭需求
r($releaseTester->setStoriesStageTest('designing', 4)) && p('stage') && e('released'); // 测试变更中需求
r($releaseTester->setStoriesStageTest('designed', 5))  && p('stage') && e('released'); // 测试设计中需求
