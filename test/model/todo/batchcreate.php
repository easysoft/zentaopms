#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->batchCreate();
cid=1
pid=1

批量创建有个没有名字的待办 >> 批量创建待办1,desc1,1,custom,0;批量创建待办2,desc2,2,custom,0;批量创建待办3,desc4,4,custom,0
批量创建的待办 >> 测试单转Bug13,desc1,1,bug,313;用户需求版本一61,desc2,2,story,1;开发任务11,desc3,3,task,1;测试单1,desc4,4,testtask,1

*/

$names  = array('批量创建待办1','批量创建待办2','', '批量创建待办3', '', '', '', '');
$types  = array('custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom');
$pris   = array('1', '2', '3', '4', '1', '2', '3', '4');
$descs  = array('desc1', 'desc2', 'desc3', 'desc4', '' , '', '', '');
$begins = array('0830', '0900', '0930', '1000', '1030', '1100', '1130', '1200');
$ends   = array('0900', '0930', '1000','1030', '1100', '1130', '1200', '1230');
$bugs   = array('2' => '313');
$assignedTos = array('user1', 'ditto', 'ditto', 'ditto', 'ditto', 'ditto', 'ditto', 'ditto',);
$noname_create = array('types' => $types, 'pris' => $pris, 'names' => $names, 'descs' => $descs, 'begins' => $begins, 'ends' => $ends, 'assignedTos' => $assignedTos);

$names     = array('批量创建待办4','批量创建待办5','批量创建待办6', '批量创建待办7', '', '', '', '');
$types     = array('bug', 'story', 'task', 'testtask', 'custom', 'custom', 'custom', 'custom');
$bugs      = array('1' => '313');
$stories   = array('2' => '1');
$tasks     = array('3' => '1');
$testtasks = array('4' => '1');

$except_create = array('types' => $types, 'pris' => $pris, 'names' => $names, 'descs' => $descs, 'begins' => $begins, 'ends' => $ends, 'bugs' => $bugs, 'stories' => $stories, 'tasks' => $tasks, 'testtasks' => $testtasks, 'assignedTos' => $assignedTos);

$todo = new todoTest();

r($todo->batchCreateTest($noname_create)) && p('2006:name,desc,pri,type,idvalue;2007:name,desc,pri,type,idvalue;2008:name,desc,pri,type,idvalue')                                 && e('批量创建待办1,desc1,1,custom,0;批量创建待办2,desc2,2,custom,0;批量创建待办3,desc4,4,custom,0'); // 批量创建有个没有名字的待办
r($todo->batchCreateTest($except_create)) && p('2009:name,desc,pri,type,idvalue;2010:name,desc,pri,type,idvalue;2011:name,desc,pri,type,idvalue;2012:name,desc,pri,type,idvalue') && e('测试单转Bug13,desc1,1,bug,313;软件需求版本一551,desc2,2,story,1;开发任务11,desc3,3,task,1;测试单1,desc4,4,testtask,1'); // 批量创建的待办
