#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProgramPriv();
timeout=0
cid=18320

- 执行searchTest模块的checkProgramPrivTest方法，参数是$results1, $objectIdList1 第1条的objectID属性 @1
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results2, $objectIdList2  @0
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results3, $objectIdList3  @0
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results4, $objectIdList4
 - 第1条的objectID属性 @1
 - 第3条的objectID属性 @3
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results5, $objectIdList5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Program1,Program2,Program3,Program4,Program5,Program6,Program7,Program8,Program9,Program10');
$project->code->range('code1,code2,code3,code4,code5,code6,code7,code8,code9,code10');
$project->type->range('program');
$project->status->range('doing');
$project->acl->range('open');
$project->parent->range('0');
$project->gen(10);

su('admin');

global $app;
$app->user->view = new stdClass();
$app->user->view->programs = '1,3,5,6,7';

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'program', 'title' => 'Test Result 1'));
$objectIdList1 = array(1 => 1);
r($searchTest->checkProgramPrivTest($results1, $objectIdList1)) && p('1:objectID') && e('1');

$results2 = array(2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'program', 'title' => 'Test Result 2'));
$objectIdList2 = array(2 => 2);
r(count($searchTest->checkProgramPrivTest($results2, $objectIdList2))) && p() && e('0');

$results3 = array();
$objectIdList3 = array();
r(count($searchTest->checkProgramPrivTest($results3, $objectIdList3))) && p() && e('0');

$results4 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'program', 'title' => 'Test Result 1'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'program', 'title' => 'Test Result 2'), 3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'program', 'title' => 'Test Result 3'), 4 => (object)array('id' => 4, 'objectID' => 4, 'objectType' => 'program', 'title' => 'Test Result 4'));
$objectIdList4 = array(1 => 1, 2 => 2, 3 => 3, 4 => 4);
r($searchTest->checkProgramPrivTest($results4, $objectIdList4)) && p('1:objectID;3:objectID') && e('1;3');

$results5 = array(1 => (object)array('id' => 1, 'objectID' => 8, 'objectType' => 'program', 'title' => 'Test Result 1'), 2 => (object)array('id' => 2, 'objectID' => 9, 'objectType' => 'program', 'title' => 'Test Result 2'), 3 => (object)array('id' => 3, 'objectID' => 10, 'objectType' => 'program', 'title' => 'Test Result 3'));
$objectIdList5 = array(8 => 1, 9 => 2, 10 => 3);
r(count($searchTest->checkProgramPrivTest($results5, $objectIdList5))) && p() && e('0');