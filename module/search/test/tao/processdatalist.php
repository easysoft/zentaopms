#!/usr/bin/env php
<?php

/**

title=测试 searchModel->processDataList();
timeout=0
cid=1

- 测试处理数据的comment第1条的comment属性 @创建bug1文件标题1.txt
- 测试处理用例的步骤
 - 第1条的desc属性 @用例步骤描述1
 - 第1条的expect属性 @这是用例预期结果1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('bug')->gen(2);

$action = zdTable('action');
$action->objectType->range('bug');
$action->objectID->range('1,2');
$action->actor->range('admin');
$action->action->range('opened');
$action->date->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD hh::mm::ss');
$action->comment->prefix('创建bug')->range('1,2');
$action->gen(2);

$file = zdTable('file');
$file->objectType->range('bug');
$file->objectID->range('1,2');
$file->gen(2);

$caseStep = zdTable('casestep');
$caseStep->case->range('1,2');
$caseStep->gen(2);

$modules = array('bug', 'case');

$bugField = new stdclass();
$bugField->id         = 'id';
$bugField->title      = 'title';
$bugField->content    = 'steps,keywords,resolvedBuild';
$bugField->addedDate  = 'openedDate';
$bugField->editedDate = 'lastEditedDate';

$caseField = new stdclass();
$caseField->id         = 'id';
$caseField->title      = 'title';
$caseField->content    = 'precondition,desc,expect';
$caseField->addedDate  = 'openedDate';
$caseField->editedDate = 'lastEditedDate';

$dataIdList = array(1, 2);

$search = new searchTest();
r($search->processDataListTest($modules[0], $bugField, $dataIdList))  && p('1:comment')     && e("创建bug1文件标题1.txt");           //测试处理数据的comment
r($search->processDataListTest($modules[1], $caseField, $dataIdList)) && p('1:desc,expect') && e("用例步骤描述1,这是用例预期结果1"); //测试处理用例的步骤