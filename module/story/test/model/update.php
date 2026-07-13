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
 - 属性estimate @1.00
- 编辑需求，判断返回的信息
 - 属性title @编辑后的名称2
 - 属性pri @2
 - 属性sourceNote @来源备注2
 - 属性estimate @2.00
- 测试编辑时在 spec 中 @用户发送 mention 通知
 - 属性notifyCount @1
 - 属性mentionUser @user1
- 测试编辑时 spec 无 mention 不发送通知 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('story')->loadYaml('story')->gen(2);
zenData('storyspec')->gen(1);
zenData('product')->gen(1);

global $app;
$app->rawModule = 'story';

$params1 = array('title' => '编辑后的名称1', 'pri' => 1, 'sourceNote' => '来源备注1', 'estimate' => 1, 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '', 'comment' => '');
$params2 = array('title' => '编辑后的名称2', 'pri' => 2, 'sourceNote' => '来源备注2', 'estimate' => 2, 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '', 'comment' => '');

$story   = new storyModelTest();
$result1 = $story->updateTest(1, $params1);
$result2 = $story->updateTest(2, $params2);

r($result1) && p('title,pri,sourceNote,estimate') && e('编辑后的名称1,1,来源备注1,1.00'); // 编辑需求，判断返回的信息
r($result2) && p('title,pri,sourceNote,estimate') && e('编辑后的名称2,2,来源备注2,2.00'); // 编辑需求，判断返回的信息

zenData('notify')->gen(0);
$user = zenData('user');
$user->account->range('admin,user1');
$user->realname->range('管理员,用户1');
$user->gen(2);

$types = array();
$types['story']         = array('mentioned');
$types['requirement']   = array('mentioned');
$tester->config->message->setting = array('message' => array('setting' => $types));

$mentionSpan = '<span class="mention-label" data-type="mention" data-id="user1">@user1</span>';
$mentionParams = array('id' => 1, 'title' => '编辑mention需求', 'spec' => $mentionSpan, 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '', 'comment' => '');
$story->updateTest(1, $mentionParams);
r($story->getLastMentionNotifyInfo()) && p('notifyCount,mentionUser') && e('1,user1'); // 测试编辑时在 spec 中提到的用户发送 mention 通知

$notifyCount = $story->getMentionNotifyCount();
$story->updateTest(2, array('id' => 1, 'title' => '无mention编辑', 'spec' => 'plain spec', 'oldDocs' => array(), 'docVersions' => array(), 'docs' => null, 'linkStories' => '', 'comment' => ''));
r($story->getMentionNotifyCount() - $notifyCount) && p() && e('0'); // 测试编辑时 spec 无 mention 不发送通知
