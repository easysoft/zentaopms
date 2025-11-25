#!/usr/bin/env php
<?php
/**

title=测试 storyModel->update();
timeout=0
cid=18593

- 编辑需求，判断返回的信息
 - 属性title @编辑后的名称1
 - 属性pri @1
 - 属性sourceNote @来源备注1
 - 属性estimate @1
- 编辑需求，判断返回的信息
 - 属性title @编辑后的名称2
 - 属性pri @2
 - 属性sourceNote @来源备注2
 - 属性estimate @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->loadYaml('story')->gen(2);
zenData('storyspec')->gen(1);
zenData('product')->gen(1);

global $app;
$app->rawModule = 'story';

$params1 = array('title' => '编辑后的名称1', 'pri' => 1, 'sourceNote' => '来源备注1', 'estimate' => 1, 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '');
$params2 = array('title' => '编辑后的名称2', 'pri' => 2, 'sourceNote' => '来源备注2', 'estimate' => 2, 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '');

$story   = new storyTest();
$result1 = $story->updateTest(1, $params1);
$result2 = $story->updateTest(2, $params2);

r($result1) && p('title,pri,sourceNote,estimate') && e('编辑后的名称1,1,来源备注1,1'); // 编辑需求，判断返回的信息
r($result2) && p('title,pri,sourceNote,estimate') && e('编辑后的名称2,2,来源备注2,2'); // 编辑需求，判断返回的信息
