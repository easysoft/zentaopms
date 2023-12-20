#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getActionForMail();
cid=0

- 传入空参数 @0
- 检查 id=1 的数据的id,extra,appendLink字段
 - 属性id @1
 - 属性extra @``
 - 属性appendLink @``
- 检查 id=1 的数据所属history的id,action字段
 - 属性id @1
 - 属性action @1
- 检查 id=2 的数据的id,extra,appendLink字段
 - 属性id @2
 - 属性extra @Fix
 - 属性appendLink @1
- 检查 id=2 的数据所属history的id,action字段
 - 属性id @2
 - 属性action @2
- 检查不存在的ID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$action = zdTable('action');
$action->execution->range(1);
$action->extra->range('``,`Fix:1`');
$action->gen(2);
zdTable('history')->gen(5);

global $tester;
$mailModel = $tester->loadModel('mail');

$action1 = $mailModel->getActionForMail(1);
$action2 = $mailModel->getActionForMail(2);

r($mailModel->getActionForMail(0))  && p() && e('0');      //传入空参数
r($action1)  && p('id,extra,appendLink') && e('1,``,``');  //检查 id=1 的数据的id,extra,appendLink字段
r($action1->history[0])  && p('id,action') && e('1,1');    //检查 id=1 的数据所属history的id,action字段
r($action2)  && p('id,extra,appendLink') && e('2,Fix,1');  //检查 id=2 的数据的id,extra,appendLink字段
r($action2->history[0])  && p('id,action') && e('2,2');    //检查 id=2 的数据所属history的id,action字段
r($mailModel->getActionForMail(10)) && p() && e('0');      //检查不存在的ID
