#!/usr/bin/env php
<?php

/**

title=测试productModel->getList();
cid=17493

- 获取项目集1下的所有产品
 - 第1条的name属性 @产品1
 - 第1条的program属性 @1
- 获取项目集1下的未关闭产品
 - 第1条的name属性 @产品1
 - 第1条的program属性 @1
- 获取项目集1下的我参与产品
 - 第1条的name属性 @产品1
 - 第1条的program属性 @1
- 获取项目集1下的激活产品
 - 第1条的name属性 @产品1
 - 第1条的program属性 @1
- 获取项目集1下的已关闭产品
 - 第21条的name属性 @产品21
 - 第21条的program属性 @1
- 获取项目集1下的10条所有产品
 - 第21条的name属性 @产品21
 - 第21条的program属性 @1
- 获取项目集1下的产品线1的所有产品
 - 第1条的name属性 @产品1
 - 第1条的line属性 @1
- 获取项目集1下的所有非影子产品
 - 第1条的name属性 @产品1
 - 第1条的shadow属性 @0
- 获取项目集1下的所有影子产品
 - 第26条的name属性 @产品26
 - 第26条的shadow属性 @1
- 获取项目集1下的所有产品数量 @30
- 获取项目集1下的未关闭产品数量 @20
- 获取项目集1下的我参与产品数量 @10
- 获取项目集1下的激活产品数量 @20
- 获取项目集1下的已关闭产品数量 @10
- 获取项目集1下的10条所有产品数量 @10
- 获取项目集1下的产品数量线1的所有产品数量 @10
- 获取项目集1下的所有非影子产品数量 @25
- 获取项目集1下的所有影子产品数量 @5
- 非管理员用户，获取项目集1下的所有产品数量 @20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('team')->gen(0);
zenData('product')->loadYaml('product')->gen(30);
zenData('project')->loadYaml('program')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
su('admin');

$programID  = 1;
$statusList = array('all', 'noclosed', 'involved', 'normal', 'closed');
$limitList  = array(0, 10);
$lineList   = array(0, 1);
$shadowList = array('all', 0, 1);

global $tester;
$tester->loadModel('product');
$tester->app->user->admin = true;
r($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[0])) && p('1:name,program')  && e('产品1,1');  // 获取项目集1下的所有产品
r($tester->product->getList($programID, $statusList[1], $limitList[0], $lineList[0], $shadowList[0])) && p('1:name,program')  && e('产品1,1');  // 获取项目集1下的未关闭产品
r($tester->product->getList($programID, $statusList[2], $limitList[0], $lineList[0], $shadowList[0])) && p('1:name,program')  && e('产品1,1');  // 获取项目集1下的我参与产品
r($tester->product->getList($programID, $statusList[3], $limitList[0], $lineList[0], $shadowList[0])) && p('1:name,program')  && e('产品1,1');  // 获取项目集1下的激活产品
r($tester->product->getList($programID, $statusList[4], $limitList[0], $lineList[0], $shadowList[0])) && p('21:name,program') && e('产品21,1'); // 获取项目集1下的已关闭产品
r($tester->product->getList($programID, $statusList[0], $limitList[1], $lineList[0], $shadowList[0])) && p('21:name,program') && e('产品21,1'); // 获取项目集1下的10条所有产品
r($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[1], $shadowList[0])) && p('1:name,line')     && e('产品1,1');  // 获取项目集1下的产品线1的所有产品
r($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[1])) && p('1:name,shadow')   && e('产品1,0');  // 获取项目集1下的所有非影子产品
r($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[2])) && p('26:name,shadow')  && e('产品26,1'); // 获取项目集1下的所有影子产品

r(count($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('30'); // 获取项目集1下的所有产品数量
r(count($tester->product->getList($programID, $statusList[1], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('20'); // 获取项目集1下的未关闭产品数量
r(count($tester->product->getList($programID, $statusList[2], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('10'); // 获取项目集1下的我参与产品数量
r(count($tester->product->getList($programID, $statusList[3], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('20'); // 获取项目集1下的激活产品数量
r(count($tester->product->getList($programID, $statusList[4], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('10'); // 获取项目集1下的已关闭产品数量
r(count($tester->product->getList($programID, $statusList[0], $limitList[1], $lineList[0], $shadowList[0]))) && p() && e('10'); // 获取项目集1下的10条所有产品数量
r(count($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[1], $shadowList[0]))) && p() && e('10'); // 获取项目集1下的产品数量线1的所有产品数量
r(count($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[1]))) && p() && e('25'); // 获取项目集1下的所有非影子产品数量
r(count($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[2]))) && p() && e('5');  // 获取项目集1下的所有影子产品数量

$tester->app->user->admin = false;
$tester->app->user->view->products = '1,2,3,4,5,6,7,8,9,10,21,22,23,24,25,26,27,28,29,30';
r(count($tester->product->getList($programID, $statusList[0], $limitList[0], $lineList[0], $shadowList[0]))) && p() && e('20'); // 非管理员用户，获取项目集1下的所有产品数量
