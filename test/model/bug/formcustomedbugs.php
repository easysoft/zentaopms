#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->formCustomedBugs();
cid=1
pid=1

获取bug 1 2的module story task case execution名称 >> 产品模块1,软件需求2,0,0,迭代1;产品模块2,软件需求6,0,0,迭代1
获取bug 3 4的module story task case execution名称 >> 产品模块3,软件需求10,0,0,迭代1;产品模块5,软件需求14,0,0,迭代2
获取bug 5 6的module story task case execution名称 >> 产品模块6,软件需求18,0,0,迭代2;产品模块7,软件需求22,0,0,迭代2
获取bug 7 8的module story task case execution名称 >> 产品模块11,软件需求26,0,0,迭代3;产品模块12,软件需求30,0,0,迭代3
获取bug 9 10的module story task case execution名称 >> 产品模块13,软件需求34,0,0,迭代3;0,软件需求38,0,0,迭代

*/

$bugIDList1 = array('1', '2');
$bugIDList2 = array('3', '4');
$bugIDList3 = array('5', '6');
$bugIDList4 = array('7', '8');
$bugIDList5 = array('9', '10');

$bug = new bugTest();
r($bug->formCustomedBugsTest($bugIDList1)) && p('1:module,story,task,case,execution;2:module,story,task,case,execution')  && e('产品模块1,软件需求2,0,0,迭代1;产品模块2,软件需求6,0,0,迭代1');     // 获取bug 1 2的module story task case execution名称
r($bug->formCustomedBugsTest($bugIDList2)) && p('3:module,story,task,case,execution;4:module,story,task,case,execution')  && e('产品模块3,软件需求10,0,0,迭代1;产品模块5,软件需求14,0,0,迭代2');   // 获取bug 3 4的module story task case execution名称
r($bug->formCustomedBugsTest($bugIDList3)) && p('5:module,story,task,case,execution;6:module,story,task,case,execution')  && e('产品模块6,软件需求18,0,0,迭代2;产品模块7,软件需求22,0,0,迭代2');   // 获取bug 5 6的module story task case execution名称
r($bug->formCustomedBugsTest($bugIDList4)) && p('7:module,story,task,case,execution;8:module,story,task,case,execution')  && e('产品模块11,软件需求26,0,0,迭代3;产品模块12,软件需求30,0,0,迭代3'); // 获取bug 7 8的module story task case execution名称
r($bug->formCustomedBugsTest($bugIDList5)) && p('9:module,story,task,case,execution;10:module,story,task,case,execution') && e('产品模块13,软件需求34,0,0,迭代3;0,软件需求38,0,0,迭代');           // 获取bug 9 10的module story task case execution名称