#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

function initData()
{
    $bug = zdTable('bug');
    $bug->id->range('1-10');
    $bug->product->range('1,2,3,4');
    $bug->branch->range('0,0,1');
    $bug->project->range('1,2');
    $bug->execution->range('1,2');
    $bug->module->range('1,0');
    $bug->status->range("resolved,active,closed");
    $bug->title->prefix("BUG")->range('1-10');
    $bug->plan->range('1,0');
    $bug->assignedTo->range('admin,zhangsan');
    $bug->openedBy->range('admin,zhangsan');
    $bug->resolvedBy->range('admin');
    $bug->confirmed->range('0,1');
    $bug->resolution->range('postponed,fixed');
    $bug->openedBuild->range('trunk,2,1');
    $bug->gen(10);
}

initData();

/**

title=bugModel->getExecutionBugs();
cid=1
pid=1

*/

$executionIdList = array(2, 1, 1000001);
$productIdList   = array(1, 2);
$branchIdList    = array('1');
$buildIdList     = array('1', 'trunk');
$typeList        = array('unresolved', 'noclosed', 'assignedtome', 'openedbyme');
$paramList       = array(1);
$excludeBugs     = array(10);

$bug = new bugTest();
r($bug->getExecutionBugsTest($executionIdList[0], 0, 'all'))                               && p('0:title;1:title;2:title;3:title;4:title') && e('BUG10;BUG8;BUG6;BUG4;BUG2'); // 测试获取项目ID为2的bug
r($bug->getExecutionBugsTest($executionIdList[0], 0, 'all', 0, 'all', 0, $excludeBugs[0])) && p('0:title;1:title;2:title;3:title')         && e('BUG8;BUG6;BUG4;BUG2');       // 测试获取项目ID为2不包含id为10的bug
r($bug->getExecutionBugsTest($executionIdList[0], 0, 'all', $buildIdList[0]))              && p('0:title;1:title;2:title')                 && e('BUG9;BUG6;BUG3');            // 测试获取项目ID为2, 影响版本为1的bug
r($bug->getExecutionBugsTest($executionIdList[0], $productIdList[1], 'all'))               && p('0:title;1:title;2:title')                 && e('BUG10;BUG6;BUG2');           // 测试获取项目ID为2,产品ID为2的bug
r($bug->getExecutionBugsTest($executionIdList[0], $productIdList[1], $branchIdList[0]))    && p('0:title')                                 && e('BUG6');                      // 测试获取项目ID为2,产品ID为2, 分支为1的bug
r($bug->getExecutionBugsTest($executionIdList[0], 0, 'all', 0, $typeList[0]))              && p('0:title;1:title')                         && e('BUG8;BUG2');                 // 测试获取项目ID为2,未解决的bug
r($bug->getExecutionBugsTest($executionIdList[0], 0, 'all', 0, $typeList[1]))              && p('0:title;1:title;2:title;3:title')         && e('BUG10;BUG8;BUG4;BUG2');      // 测试获取项目ID为2,未关闭的bug
r($bug->getExecutionBugsTest($executionIdList[1], 0, 'all', 0, 'all', $paramList[0]))      && p('0:title;1:title;2:title;3:title;4:title') && e('BUG9;BUG7;BUG5;BUG3;BUG1');  // 测试获取项目ID为1,模块为1的bug
