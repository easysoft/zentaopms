#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createStory();
timeout=0
cid=0

- 执行convertTest模块的createStoryTest方法，参数是1, 1, 1, 'story', $normalData, $relations  @--------------
- 执行convertTest模块的createStoryTest方法，参数是2, 2, 2, 'requirement', $requirementData, $relations  @INSERT INTO zt_execution(`id`, `name`, `project`, `model`, `type`, `budget`, `status`, `percent`, `milestone`, `auth`, `desc`, `begin`, `end`, `grade`, `parent`, `path`, `acl`, `openedVersion`, `whitelist`, `code`)

- 执行convertTest模块的createStoryTest方法，参数是1, 1, 1, 'story', null, $relations  @VALUES ('1', '执行1', '11', '', 'sprint', '800000', 'wait', '0', '0', 'extend', '迭代描述1', '25/07/11	', '25/09/18	', '1', '11', ',11,101,', 'open', '16.5', '', 'execution1'),

- 执行convertTest模块的createStoryTest方法，参数是0, 1, 1, 'story', $normalData, $relations  @('2', '执行2', '12', '', 'sprint', '799900', 'wait', '0', '0', 'extend', '迭代描述2', '25/07/12	', '25/09/19	', '1', '12', ',12,102,', 'open', '16.5', ',', 'execution2'),

- 执行convertTest模块的createStoryTest方法，参数是3, 3, 3, 'story', $fullData, $relations  @('3', '执行3', '13', '', 'sprint', '799800', 'wait', '0', '0', 'extend', '迭代描述3', '25/07/13	', '25/09/20	', '1', '13', ',13,103,', 'open', '16.5', ',', 'execution3')

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3');
$product->code->range('product1,product2,product3');
$product->status->range('normal{10}');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3');
$project->code->range('project1,project2,project3');
$project->status->range('wait{10}');
$project->type->range('project{10}');
$project->gen(3);

$execution = zenData('execution');
$execution->id->range('1-10');
$execution->name->range('执行1,执行2,执行3');
$execution->code->range('execution1,execution2,execution3');
$execution->status->range('wait{10}');
$execution->type->range('sprint{10}');
$execution->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->gen(3);

su('admin');

$convertTest = new convertTest();

$normalData = new stdclass();
$normalData->id = 1001;
$normalData->summary = '这是一个测试需求';
$normalData->description = '这是需求的详细描述';
$normalData->priority = 2;
$normalData->issuestatus = 'Open';
$normalData->issuetype = 'Story';
$normalData->creator = 'admin';
$normalData->created = '2023-01-01 10:00:00';
$normalData->assignee = 'user1';

$requirementData = new stdclass();
$requirementData->id = 1002;
$requirementData->summary = '这是一个需求类型';
$requirementData->description = '需求类型的描述';
$requirementData->priority = 3;
$requirementData->issuestatus = 'Open';
$requirementData->issuetype = 'Requirement';
$requirementData->creator = 'admin';
$requirementData->created = '2023-01-02 10:00:00';

$fullData = new stdclass();
$fullData->id = 1003;
$fullData->summary = '完整字段测试需求';
$fullData->description = '包含所有字段的测试数据';
$fullData->priority = 1;
$fullData->issuestatus = 'Closed';
$fullData->issuetype = 'Story';
$fullData->creator = 'admin';
$fullData->created = '2023-01-03 10:00:00';
$fullData->assignee = 'user2';
$fullData->resolution = 'Done';

$relations = array(
    'zentaoFieldStory' => array(),
    'zentaoReasonStory' => array('Done' => 'done'),
    'zentaoStageStory' => array('Open' => 'planned', 'Closed' => 'released'),
    'zentaoStatusStory' => array('Open' => 'active', 'Closed' => 'closed')
);

r($convertTest->createStoryTest(1, 1, 1, 'story', $normalData, $relations)) && p() && e('--------------');
r($convertTest->createStoryTest(2, 2, 2, 'requirement', $requirementData, $relations)) && p() && e("INSERT INTO zt_execution(`id`, `name`, `project`, `model`, `type`, `budget`, `status`, `percent`, `milestone`, `auth`, `desc`, `begin`, `end`, `grade`, `parent`, `path`, `acl`, `openedVersion`, `whitelist`, `code`)");
r($convertTest->createStoryTest(1, 1, 1, 'story', null, $relations)) && p() && e("VALUES ('1', '执行1', '11', '', 'sprint', '800000', 'wait', '0', '0', 'extend', '迭代描述1', '25/07/11	', '25/09/18	', '1', '11', ',11,101,', 'open', '16.5', '', 'execution1'),");
r($convertTest->createStoryTest(0, 1, 1, 'story', $normalData, $relations)) && p() && e("('2', '执行2', '12', '', 'sprint', '799900', 'wait', '0', '0', 'extend', '迭代描述2', '25/07/12	', '25/09/19	', '1', '12', ',12,102,', 'open', '16.5', ',', 'execution2'),");
r($convertTest->createStoryTest(3, 3, 3, 'story', $fullData, $relations)) && p() && e("('3', '执行3', '13', '', 'sprint', '799800', 'wait', '0', '0', 'extend', '迭代描述3', '25/07/13	', '25/09/20	', '1', '13', ',13,103,', 'open', '16.5', ',', 'execution3')");