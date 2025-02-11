#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=变更需求测试
timeout=0
cid=80

*/
chdir(__DIR__);
include '../lib/changestory.ui.class.php';

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
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
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

$tester = new changeStoryTester();
$tester->login();

$storys = array();
$storys['null']    = '';
$storys['default'] = '变更后需求';

$reviewer = array();
$reviewer['null']    = array();
$reviewer['default'] = array('admin');

r($tester->changeStory($storys['default'], $reviewer['null']))    && p('message,status') && e('变更需求表单页面评审人不为空提示信息正确,SUCCESS'); // 缺少评审人，变更失败
r($tester->changeStory($storys['null'], $reviewer['default']))    && p('message,status') && e('变更需求表单页面需求名称不为空提示信息正确,SUCCESS'); // 缺少需求名称，变更失败
r($tester->changeStory($storys['default'], $reviewer['default'])) && p('message,status') && e('变更需求成功,SUCCESS');                 // 使用默认选项变更需求，详情页信息对应

r($tester->changeEpic($storys['default'], $reviewer['null']))    && p('message,status') && e('变更需求表单页面评审人不为空提示信息正确,SUCCESS'); // 缺少评审人，变更失败
r($tester->changeEpic($storys['null'], $reviewer['default']))    && p('message,status') && e('变更需求表单页面需求名称不为空提示信息正确,SUCCESS'); // 缺少需求名称，变更失败
r($tester->changeEpic($storys['default'], $reviewer['default'])) && p('message,status') && e('变更需求成功,SUCCESS');                 // 使用默认选项变更需求,详情页信息对应

r($tester->changeRequirement($storys['default'], $reviewer['null']))    && p('message,status') && e('变更需求表单页面评审人不为空提示信息正确,SUCCESS'); // 缺少评审人，变更失败
r($tester->changeRequirement($storys['null'], $reviewer['default']))    && p('message,status') && e('变更需求表单页面需求名称不为空提示信息正确,SUCCESS'); // 缺少需求名称，变更失败
r($tester->changeRequirement($storys['default'], $reviewer['default'])) && p('message,status') && e('变更需求成功,SUCCESS');                 // 使用默认选项变更需求,详情页信息对应

$tester->closeBrowser();
