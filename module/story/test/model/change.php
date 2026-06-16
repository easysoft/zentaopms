#!/usr/bin/env php
<?php

/**

title=测试 storyModel->change();
timeout=0
cid=18479

- 查看变更后需求数据。
 - 属性title @测试需求1变更标题
 - 属性spec @测试需求1的变更描述
 - 属性version @4
- 变更时不填写需求名称，给出提示 @1
- 测试存在relievedTwins。属性title @测试需求1变更标题
- 测试变成需求。属性title @名称修改
- 测试变更时在 spec 中 @用户发送 mention 通知
 - 属性notifyCount @1
 - 属性mentionUser @user1
- 测试变更时 spec 无 mention 不发送通知 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('story')->gen(30);
zenData('storyspec')->gen(90);
zenData('doc')->gen(30);
zenData('notify')->gen(0);

$user = zenData('user');
$user->account->range('admin,user1');
$user->realname->range('管理员,用户1');
$user->gen(2);


$story  = new storyModelTest();

$tester->config->requirement = $tester->config->story;

$story1 = new stdclass();
$story1->id                 = 1;
$story1->title              = '测试需求1变更标题';
$story1->spec               = '测试需求1的变更描述';
$story1->verify             = '测试需求1的变更验收标准';
$story1->deleteFiles        = array();
$story1->reviewerHasChanged = '';
$story1->estimate           = 1;
$story1->reviewer           = array();
$story1->version            = 4;
$story1->docs               = '1';
$story1->oldDocs            = array(1);
$story1->docVersions        = '1';

$story2 = clone $story1;
$story2->reviewer = array('admin', 'test2');
$story2->title    = '';
$story2->version  = 5;

$story3 = clone $story1;
$story3->title = '';

$story4 = clone $story1;
$story4->relievedTwins = true;

$story5 = clone $story1;
$story5->title = '名称修改';

$mentionStory  = clone $story1;
$mentionStory->title   = 'mention变更标题';
$mentionStory->spec    = '<span class="mention-label" data-type="mention" data-id="user1">@user1</span>';
$mentionStory->version = 5;

$plainStory  = clone $story1;
$plainStory->title   = 'plain变更标题';
$plainStory->spec    = 'plain spec';
$plainStory->version = 6;

$result = $story->changeTest(2,  $story2);

r($story->changeTest(1, $story1))                               && p('title,spec,version') && e('测试需求1变更标题,测试需求1的变更描述,4'); // 查看变更后需求数据。
r((int)strpos($result['title'][0], '名称』不能为空') !== false) && p()                     && e('1');                                       // 变更时不填写需求名称，给出提示
r($story->changeTest(3, $story4))                               && p('title')              && e('测试需求1变更标题');                       // 测试存在relievedTwins。
r($story->changeTest(4, $story5))                               && p('title')              && e('名称修改');                                // 测试变成需求。

$types = array();
$types['story']       = array('mentioned');
$types['requirement'] = array('mentioned');
$tester->config->message->setting = array('message' => array('setting' => $types));

$story->changeTest(5, $mentionStory);
r($story->getLastMentionNotifyInfo()) && p('notifyCount,mentionUser') && e('1,user1'); // 测试变更时在 spec 中提到的用户发送 mention 通知

$notifyCount = $story->getMentionNotifyCount();
$story->changeTest(11, $plainStory);
r($story->getMentionNotifyCount() - $notifyCount) && p() && e('0'); // 测试变更时 spec 无 mention 不发送通知
