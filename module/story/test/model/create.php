#!/usr/bin/env php
<?php
/**

title=测试 storyModel->create();
timeout=0
cid=18486

- 检查创建后的数据。
 - 属性id @5
 - 属性title @test story
- 如果传入执行，检查需求是否已经关联到执行了。 @1
- 如果传入执行，检查执行信息。
 - 属性project @11
 - 属性product @1
 - 属性story @6
- 如果传入Bug，检查Bug是否已经关闭了。 @closed
- 测试创建时在 spec 中 @用户发送 mention 通知
 - 属性notifyCount @1
 - 属性mentionUser @user1
 - 属性status @wait
 - 属性createdBy @admin
- 测试创建时 spec 无 mention 不发送通知 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('product')->gen(20);
zenData('project')->gen(20);
zenData('bug')->gen(2);
zenData('relation')->gen(0);
zenData('storyreview')->gen(0);
zenData('projectstory')->gen(0);

$story = zenData('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0');
$story->product->range('1');
$story->version->range('1');
$story->gen(4);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-6');
$storySpec->gen(4);

$data  = new stdclass();
$data->product     = 1;
$data->module      = 0;
$data->modules     = array(0);
$data->plan        = '1';
$data->assignedTo  = '';
$data->source      = '';
$data->sourceNote  = '';
$data->feedbackBy  = '';
$data->notifyEmail = '';
$data->type        = 'story';
$data->parent      = 0;
$data->title       = 'test story';
$data->color       = '';
$data->category    = 'feature';
$data->pri         = 3;
$data->estimate    = 1;
$data->spec        = 'test spec';
$data->verify      = 'test verify';
$data->keywords    = '';
$data->type        = 'story';
$data->status      = 'active';
$data->version     = 1;
$data->openedBy    = 'admin';
$data->openedDate  = date('Y-m-d H:i:s');
$data->mailto      = '';
$data->parent      = 1;
$data->reviewer[]  = 'admin';

$story = new storyModelTest();
$test1 = $story->createTest($data);
$test2 = $story->createTest($data, 11);
$test3 = $story->createTest($data, 0, 1);
r((array)$test1)                 && p('id,title')              && e('5,test story'); // 检查创建后的数据。
r(count($test2->linkedProjects)) && p()                        && e('1');            // 如果传入执行，检查需求是否已经关联到执行了。
r($test2->linkedProjects[0])     && p('project,product,story') && e('11,1,6');       // 如果传入执行，检查执行信息。
r($test3->linkedBug->status)     && p()                        && e('closed');       // 如果传入Bug，检查Bug是否已经关闭了。

zenData('notify')->gen(0);
$user = zenData('user');
$user->account->range('admin,user1');
$user->realname->range('管理员,用户1');
$user->gen(2);

$types = array();
$types['story']       = array('mentioned');
$types['requirement'] = array('mentioned');
$tester->config->message->setting = array('message' => array('setting' => $types));

$mentionData = clone $data;
$mentionData->title = 'mention story';
$mentionData->spec  = '<span class="mention-label" data-type="mention" data-id="user1">@user1</span>';
$story->createTest($mentionData);
r($story->getLastMentionNotifyInfo()) && p('notifyCount,mentionUser,status,createdBy') && e('1,user1,wait,admin'); // 测试创建时在 spec 中提到的用户发送 mention 通知

$notifyCount = $story->getMentionNotifyCount();
$plainData   = clone $data;
$plainData->title = 'plain story';
$plainData->spec  = 'plain spec';
$story->createTest($plainData);
r($story->getMentionNotifyCount() - $notifyCount) && p() && e('0'); // 测试创建时 spec 无 mention 不发送通知
