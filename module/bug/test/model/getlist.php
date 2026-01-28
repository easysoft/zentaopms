#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=bugModel->getList();
timeout=0
cid=15385

- 获取全部产品1下的全部bug列表，查看数量是否正确 @5
- 获取产品1模块下的bug列表，查看数量是否正确 @5
- 获取产品1下指派给我的bug列表，查看数量是否正确 @5
- 获取产品1下由我创建的bug列表，查看数量是否正确 @5
- 获取产品1下由我解决的bug列表，查看数量是否正确 @5
- 获取产品1下未指派的bug列表，查看数量是否正确 @0
- 获取产品1下未确认的bug列表，查看数量是否正确 @5
- 获取产品1下未解决的bug列表，查看数量是否正确 @1
- 获取产品1下未关闭的bug列表，查看数量是否正确 @3
- 获取产品1下待关闭的bug列表，查看数量是否正确 @2
- 获取产品1下被延期的bug列表，查看数量是否正确 @5
- 获取不存在产品ID的bug列表，查看数量是否正确 @0
- 获取不存在项目ID的bug列表，查看数量是否正确 @0
- 获取不存在执行ID的bug列表，查看数量是否正确 @5
- 获取全部产品1下的项目相关的全部bug列表，查看ID为2的bug的名称是否正确第2条的title属性 @BUG2
- 获取产品1模块下的bug列表，查看ID为3的bug的module是否正确第3条的module属性 @1
- 获取产品1下指派给我的bug列表，查看ID为1的bug的指派人是否正确第1条的assignedTo属性 @admin
- 获取产品1下由我创建的bug列表，查看ID为3的bug的创建者是否正确第3条的openedBy属性 @admin
- 获取产品1下由我解决的bug列表，查看ID为5的bug的解决者是否正确第5条的resolvedBy属性 @admin
- 获取产品1下未确认的bug列表，查看ID为3的bug的是否确认字段是否正确第3条的confirmed属性 @0
- 获取产品1下久未处理的bug列表，查看ID为5的bug的状态是否正确第5条的status属性 @active
- 获取产品1下未关闭的bug列表，查看ID为7的bug的状态是否正确第7条的status属性 @resolved
- 获取产品1下待关闭的bug列表，查看ID为7的bug的状态是否正确第1条的status属性 @resolved
- 获取产品1下被延期的bug列表，查看数量是否正确第3条的resolution属性 @postponed

*/

function initData()
{
    $bug = zenData('bug');
    $bug->id->range('1-10');
    $bug->product->range('1,2');
    $bug->branch->range('0,1');
    $bug->project->range('0,2');
    $bug->execution->range('0,3');
    $bug->module->range('1,0');
    $bug->status->range("resolved,active,closed");
    $bug->title->prefix("BUG")->range('1-10');
    $bug->plan->range('1,0');
    $bug->assignedTo->range('admin');
    $bug->openedBy->range('admin');
    $bug->resolvedBy->range('admin');
    $bug->confirmed->range('0,1');
    $bug->resolution->range('postponed,fixed');
    $bug->gen(10);

    $productplan = zenData('productplan');
    $productplan->id->range('1');
    $productplan->product->range('1');
    $productplan->title->range('计划1');
    $productplan->gen(1);
}

initData();

$browseType      = array('all', 'bymodule', 'assigntome', 'openedbyme', 'resolvedbyme', 'assigntonull', 'unconfirmed', 'unresolved', 'unclosed', 'toclosed', 'postponedbugs', 'assignedbyme');
$productIdList   = array(array(1), array(2), array(1000001));
$projectID       = array('0', '2', '1000001');
$executionIdList = array(array(), array('3'), array('1000001'));
$branch          = array('0', '1', '1000001');
$moduleID        = array('0', '1', '1000001');

global $tester;
$bug = $tester->loadModel('bug');
r(count($bug->getList($browseType[0],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取全部产品1下的全部bug列表，查看数量是否正确
r(count($bug->getList($browseType[1],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[1]))) && p('') && e('5'); //获取产品1模块下的bug列表，查看数量是否正确
r(count($bug->getList($browseType[2],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取产品1下指派给我的bug列表，查看数量是否正确
r(count($bug->getList($browseType[3],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取产品1下由我创建的bug列表，查看数量是否正确
r(count($bug->getList($browseType[4],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取产品1下由我解决的bug列表，查看数量是否正确
r(count($bug->getList($browseType[5],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('0'); //获取产品1下未指派的bug列表，查看数量是否正确
r(count($bug->getList($browseType[6],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取产品1下未确认的bug列表，查看数量是否正确
r(count($bug->getList($browseType[7],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('1'); //获取产品1下未解决的bug列表，查看数量是否正确
r(count($bug->getList($browseType[8],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('3'); //获取产品1下未关闭的bug列表，查看数量是否正确
r(count($bug->getList($browseType[9],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('2'); //获取产品1下待关闭的bug列表，查看数量是否正确
r(count($bug->getList($browseType[10], $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取产品1下被延期的bug列表，查看数量是否正确
r(count($bug->getList($browseType[0],  $productIdList[2], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('0'); //获取不存在产品ID的bug列表，查看数量是否正确
r(count($bug->getList($browseType[0],  $productIdList[0], $projectID[2], $executionIdList[0], $branch[0], $moduleID[0]))) && p('') && e('0'); //获取不存在项目ID的bug列表，查看数量是否正确
r(count($bug->getList($browseType[0],  $productIdList[0], $projectID[0], $executionIdList[2], $branch[0], $moduleID[0]))) && p('') && e('5'); //获取不存在执行ID的bug列表，查看数量是否正确

r($bug->getList($browseType[0],  $productIdList[1], $projectID[1], $executionIdList[1], $branch[1], $moduleID[0])) && p('2:title')      && e('BUG2');      //获取全部产品1下的项目相关的全部bug列表，查看ID为2的bug的名称是否正确
r($bug->getList($browseType[1],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[1])) && p('3:module')     && e('1');         //获取产品1模块下的bug列表，查看ID为3的bug的module是否正确
r($bug->getList($browseType[2],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('1:assignedTo') && e('admin');     //获取产品1下指派给我的bug列表，查看ID为1的bug的指派人是否正确
r($bug->getList($browseType[3],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('3:openedBy')   && e('admin');     //获取产品1下由我创建的bug列表，查看ID为3的bug的创建者是否正确
r($bug->getList($browseType[4],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('5:resolvedBy') && e('admin');     //获取产品1下由我解决的bug列表，查看ID为5的bug的解决者是否正确
r($bug->getList($browseType[6],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('3:confirmed')  && e('0');         //获取产品1下未确认的bug列表，查看ID为3的bug的是否确认字段是否正确
r($bug->getList($browseType[7],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('5:status')     && e('active');    //获取产品1下久未处理的bug列表，查看ID为5的bug的状态是否正确
r($bug->getList($browseType[8],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('7:status')     && e('resolved');  //获取产品1下未关闭的bug列表，查看ID为7的bug的状态是否正确
r($bug->getList($browseType[9],  $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('1:status')     && e('resolved');  //获取产品1下待关闭的bug列表，查看ID为7的bug的状态是否正确
r($bug->getList($browseType[10], $productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0])) && p('3:resolution') && e('postponed'); //获取产品1下被延期的bug列表，查看数量是否正确