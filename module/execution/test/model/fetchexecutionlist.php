#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution', true)->gen(30);
zenData('product')->loadYaml('product', true)->gen(30);
zenData('projectproduct')->loadYaml('projectproduct', true)->gen(60);

$userquery = zenData('userquery');
$userquery->id->range(1);
$userquery->account->range('admin');
$userquery->module->range('execution');
$userquery->title->range('搜索进行中的迭代');
$userquery->sql->range("`(( 1  AND `status` = 'doing' ))`");
$userquery->form->range('``');
$userquery->gen(1);

/**

title=测试 executionModel->fetchExecutionList();
timeout=0
cid=16291

- 获取没有限制条件时执行的个数 @20
- 获取进行中执行的个数 @11
- 获取敏捷项目1下的执行的个数 @3
- 获取全部状态执行的个数 @24
- 获取未关闭的执行的个数 @20
- 获取未开始的执行的个数 @6
- 获取进行中的执行的个数 @11
- 获取已暂停的执行的个数 @3
- 获取已关闭的执行的个数 @4
- 获取我参与的执行的个数 @24
- 获取关联产品5的执行的个数 @1
- 获取敏捷项目下全部的执行
 - 第101条的project属性 @11
 - 第101条的name属性 @迭代5
- 获取敏捷项目下未关闭的执行
 - 第101条的project属性 @11
 - 第101条的name属性 @迭代5
- 获取敏捷项目下未开始的执行
 - 第101条的project属性 @11
 - 第101条的name属性 @迭代5
- 获取敏捷项目下进行中的执行
 - 第103条的project属性 @11
 - 第103条的name属性 @迭代7
- 获取敏捷项目下已暂停的执行 @0
- 获取敏捷项目下已关闭的执行 @0
- 获取敏捷项目下我参与的执行
 - 第103条的project属性 @11
 - 第103条的name属性 @迭代7
- 获取敏捷项目下按照开始日期倒序排列全部的执行
 - 第103条的project属性 @11
 - 第103条的name属性 @迭代7
- 获取按照开始日期倒序排列执行
 - 第126条的project属性 @100
 - 第126条的name属性 @看板30

*/

$projectIdList  = array(0, 11);
$browseTypeList = array('all', 'undone', 'wait', 'doing', 'suspended', 'closed', 'involved', 'bySearch');
$productIdList  = array(0, 5);
$paramList      = array(0, 1);
$orderByList    = array('id_asc', 'begin_desc');

global $tester;
$executionModel = $tester->loadModel('execution');

$allExecutions             = $executionModel->fetchExecutionList();
$searchExecutions          = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[7], $productIdList[0], $paramList[1], $orderByList[0]);
$projectExecutions         = $executionModel->fetchExecutionList($projectIdList[1]);
$allStatusExecutions       = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[0]);
$undoneStatusExecutions    = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[1]);
$waitStatusExecutions      = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[2]);
$doingStatusExecutions     = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[3]);
$suspendedStatusExecutions = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[4]);
$closedStatusExecutions    = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[5]);
$involvedExecutions        = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[6]);
$productExecutions         = $executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[1], $productIdList[1]);

r(count($allExecutions))             && p() && e('20'); // 获取没有限制条件时执行的个数
r(count($searchExecutions))          && p() && e('11'); // 获取进行中执行的个数
r(count($projectExecutions))         && p() && e('3');  // 获取敏捷项目1下的执行的个数
r(count($allStatusExecutions))       && p() && e('24'); // 获取全部状态执行的个数
r(count($undoneStatusExecutions))    && p() && e('20'); // 获取未关闭的执行的个数
r(count($waitStatusExecutions))      && p() && e('6');  // 获取未开始的执行的个数
r(count($doingStatusExecutions))     && p() && e('11'); // 获取进行中的执行的个数
r(count($suspendedStatusExecutions)) && p() && e('3');  // 获取已暂停的执行的个数
r(count($closedStatusExecutions))    && p() && e('4');  // 获取已关闭的执行的个数
r(count($involvedExecutions))        && p() && e('24'); // 获取我参与的执行的个数
r(count($productExecutions))         && p() && e('1');  // 获取关联产品5的执行的个数

r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[0], $productIdList[0], $paramList[0], $orderByList[0])) && p('101:project,name') && e('11,迭代5');   // 获取敏捷项目下全部的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[1], $productIdList[0], $paramList[0], $orderByList[0])) && p('101:project,name') && e('11,迭代5');   // 获取敏捷项目下未关闭的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[2], $productIdList[0], $paramList[0], $orderByList[0])) && p('101:project,name') && e('11,迭代5');   // 获取敏捷项目下未开始的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[3], $productIdList[0], $paramList[0], $orderByList[0])) && p('103:project,name') && e('11,迭代7');   // 获取敏捷项目下进行中的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[4], $productIdList[0], $paramList[0], $orderByList[0])) && p()                   && e('0');          // 获取敏捷项目下已暂停的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[5], $productIdList[0], $paramList[0], $orderByList[0])) && p()                   && e('0');          // 获取敏捷项目下已关闭的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[6], $productIdList[0], $paramList[0], $orderByList[0])) && p('103:project,name') && e('11,迭代7');   // 获取敏捷项目下我参与的执行
r($executionModel->fetchExecutionList($projectIdList[1], $browseTypeList[0], $productIdList[0], $paramList[0], $orderByList[1])) && p('103:project,name') && e('11,迭代7');   // 获取敏捷项目下按照开始日期倒序排列全部的执行
r($executionModel->fetchExecutionList($projectIdList[0], $browseTypeList[1], $productIdList[0], $paramList[0], $orderByList[1])) && p('126:project,name') && e('100,看板30'); // 获取按照开始日期倒序排列执行