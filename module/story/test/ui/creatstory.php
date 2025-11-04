#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=创建研发需求测试
timeout=0
cid=80

- 创建研发需求名称必填校验
 - 测试结果@创建需求页面名称为空提示正确
 - 最终测试状态@SUCCESS
- 创建研发需求
 - 测试结果@创建研发需求成功
 - 最终测试状态@SUCCESS
- 创建用户需求
 - 测试结果@创建用户需求成功
 - 最终测试状态@SUCCESS
- 创建业务需求
 - 测试结果@创建业务需求成功
 - 最终测试状态@SUCCESS
- 批量创建研发需求名称必填校验
 - 测试结果@批量创建需求页面名称为空提示正确
 - 最终测试状态@SUCCESS
- 批量创建研发需求
 - 测试结果@批量创建研发需求成功
 - 最终测试状态@SUCCESS
- 批量创建用户需求
 - 测试结果@批量创建用户需求成功
 - 最终测试状态@SUCCESS
- 批量创建业务需求
 - 测试结果@批量创建业务需求成功
 - 最终测试状态@SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createstory.ui.class.php';
include 'page/create.php';
include 'page/batchcreate.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-3');
$story->root->range('1-3');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(3);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-3');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求,激活用户需求,激活业务需求');
$storyspec->gen(3);

$storyreview = zenData('storyreview');
$storyreview->gen(0);

$action = zenData('action');
$action->id->range('1-4');
$action->objectType->range('product,story,story,story');
$action->objectID->range('1,1,2,3');
$action->product->range('`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(4);

$tester = new createStoryTester();
$tester->login();

$storys = array();
$storys['null']             = '';
$storys['story']            = '研发需求';
$storys['requirement']      = '用户需求';
$storys['epic']             = '业务需求';
$storys['childStory']       = '子需求';
$storys['batchstory']       = '批量创建研发需求';
$storys['batchrequirement'] = '批量创建用户需求';
$storys['batchepic']        = '批量创建业务需求';

$storyType = array();
$storyType['epic']        = 'epic';
$storyType['requirement'] = 'requirement';
$storyType['story']       = 'story';

r($tester->createDefault($storyType['story'], $storys['null']))              && p('message,status') && e('创建需求页面名称为空提示正确,SUCCESS'); // 缺少需求名称，创建失败
r($tester->createDefault($storyType['story'], $storys['story']))             && p('message,status') && e('创建研发需求成功,SUCCESS'); // 使用默认选项创建需求,搜索后详情页信息对应
r($tester->createDefault($storyType['requirement'], $storys['requirement'])) && p('message,status') && e('创建用户需求成功,SUCCESS'); // 使用默认选项创建用户需求，搜索后详情页信息对应
r($tester->createDefault($storyType['epic'], $storys['epic']))               && p('message,status') && e('创建业务需求成功,SUCCESS'); // 使用默认选项创建业务需求，搜索后详情页信息对应

r($tester->batchCreateDefault($storyType['story'], $storys['null']))                   && p('message,status') && e('批量创建需求页面名称为空提示正确,SUCCESS'); // 缺少需求名称，创建失败
r($tester->batchcreateDefault($storyType['story'], $storys['batchstory']))             && p('message,status') && e('批量创建研发需求成功,SUCCESS'); // 使用默认选项批量创建需求,搜索后详情页信息对应
r($tester->batchcreateDefault($storyType['requirement'], $storys['batchrequirement'])) && p('message,status') && e('批量创建用户需求成功,SUCCESS'); // 使用默认选项批量创建用户需求，搜索后详情页信息对应
r($tester->batchcreateDefault($storyType['epic'], $storys['batchepic']))               && p('message,status') && e('批量创建业务需求成功,SUCCESS'); // 使用默认选项批量创建业务需求，搜索后详情页信息对应

$tester->closeBrowser();
