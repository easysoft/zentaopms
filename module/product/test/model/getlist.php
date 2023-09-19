#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
zdTable('team')->gen(0);
zdTable('product')->config('product')->gen(30);
zdTable('project')->config('program')->gen(10);
zdTable('projectproduct')->config('projectproduct')->gen(30);
su('admin');

/**

title=测试productModel->getList();
timeout=0
cid=1

*/

$programID  = 1;
$statusList = array('all', 'noclosed', 'involved', 'normal', 'closed');
$limitList  = array(0, 10);
$lineList   = array(0, 1);
$shadowList = array('all', 0, 1);

global $tester;
$tester->loadModel('product');
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
