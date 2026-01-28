#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('bug')->gen(82);
zenData('user')->gen(10);
zenData('project')->loadYaml('project_confirm')->gen(100);
zenData('product')->gen(82);

/**

title=bugModel->confirm();
timeout=0
cid=15349

- 确认指派人变化的bug
 - 第1条的field属性 @assignedTo
 - 第1条的old属性 @admin
 - 第1条的new属性 @user92

- 确认类型变化的bug
 - 第0条的field属性 @type
 - 第0条的old属性 @install
 - 第0条的new属性 @codeerror

- 确认已确认的bug
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @admin
 - 第0条的new属性 @user95

- 确认优先级变化的bug
 - 第0条的field属性 @pri
 - 第0条的old属性 @3
 - 第0条的new属性 @2

- 确认bug
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @active

*/

$bug1 = array('id' => 1,  'assignedTo' => 'user92', 'status' => 'active', 'type' => 'codeerror', 'pri' => '1');
$bug3 = array('id' => 3,  'assignedTo' => 'admin' , 'status' => 'active', 'type' => 'codeerror', 'pri' => '3');
$bug4 = array('id' => 4,  'assignedTo' => 'user95', 'status' => 'active', 'type' => 'security',  'pri' => '4');
$bug5 = array('id' => 51, 'assignedTo' => 'dev1'  , 'status' => 'active', 'type' => 'standard',  'pri' => '2');
$bug8 = array('id' => 81, 'assignedTo' => 'test1' , 'status' => 'active', 'type' => 'others',    'pri' => '1');

$bug = new bugModelTest();
r($bug->confirmTest($bug1)) && p('1:field,old,new') && e('assignedTo,admin,user92'); // 确认指派人变化的bug
r($bug->confirmTest($bug3)) && p('0:field,old,new') && e('type,install,codeerror');  // 确认类型变化的bug
r($bug->confirmTest($bug4)) && p('0:field,old,new') && e('assignedTo,admin,user95'); // 确认已确认的bug
r($bug->confirmTest($bug5)) && p('0:field,old,new') && e('pri,3,2');                 // 确认优先级变化的bug
r($bug->confirmTest($bug8)) && p('0:field,old,new') && e('status,closed,active');    // 确认bug
