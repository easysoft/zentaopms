#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=mrModel->getToAndCcList();
timeout=0
cid=1

- 计算有指派者和创建者的情况
 -  @admin
 - 属性1 @user1
- 计算无指派者有创建者的情况
 -  @admin
 - 属性1 @~~
- 计算无指派者无创建者的情况
 -  @~~
 - 属性1 @~~
- 计算有指派者无创建者的情况
 -  @~~
 - 属性1 @user1

*/

$MR = new stdclass();
$MR->assignee  = 'user1';
$MR->createdBy = 'admin';

global $tester;
$mrModel = $tester->loadModel('mr');
r($mrModel->getToAndCcList($MR)) && p('0,1') && e('admin,user1'); // 计算有指派者和创建者的情况

$MR->assignee = '';
r($mrModel->getToAndCcList($MR)) && p('0,1') && e('admin,~~'); // 计算无指派者有创建者的情况

$MR->createdBy = '';
r($mrModel->getToAndCcList($MR)) && p('0,1') && e('~~,~~'); // 计算无指派者无创建者的情况

$MR->assignee  = 'user1';
r($mrModel->getToAndCcList($MR)) && p('0,1') && e('~~,user1'); // 计算有指派者无创建者的情况