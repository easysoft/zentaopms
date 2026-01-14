#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

function initData()
{
    $bug = zenData('bug');
    $bug->id->range('1-10');
    $bug->product->range('1,2,3,4');
    $bug->branch->range('0,0,1');
    $bug->project->range('1,2');
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

title=bugModel->getProjectBugs();
timeout=0
cid=15392

- 测试获取项目ID为2的bug
 - 第10条的title属性 @BUG10
 - 第8条的title属性 @BUG8
 - 第6条的title属性 @BUG6
 - 第4条的title属性 @BUG4
 - 第2条的title属性 @BUG2
- 测试获取项目ID为2不包含id为10的bug
 - 第8条的title属性 @BUG8
 - 第6条的title属性 @BUG6
 - 第4条的title属性 @BUG4
 - 第2条的title属性 @BUG2
- 测试获取项目ID为2, 影响版本为1的bug
 - 第9条的title属性 @BUG9
 - 第6条的title属性 @BUG6
 - 第3条的title属性 @BUG3
- 测试获取项目ID为2,产品ID为2的bug
 - 第10条的title属性 @BUG10
 - 第6条的title属性 @BUG6
 - 第2条的title属性 @BUG2
- 测试获取项目ID为2,产品ID为2, 分支为1的bug第6条的title属性 @BUG6
- 测试获取项目ID为2,未解决的bug
 - 第8条的title属性 @BUG8
 - 第2条的title属性 @BUG2
- 测试获取项目ID为2,未关闭的bug
 - 第10条的title属性 @BUG10
 - 第8条的title属性 @BUG8
 - 第4条的title属性 @BUG4
 - 第2条的title属性 @BUG2
- 测试获取项目ID为1,模块为1的bug
 - 第9条的title属性 @BUG9
 - 第7条的title属性 @BUG7
 - 第5条的title属性 @BUG5
 - 第3条的title属性 @BUG3
 - 第1条的title属性 @BUG1

*/

$projectIdList = array(2, 1, 1000001);
$productIdList = array(1, 2);
$branchIdList  = array('1');
$buildIdList   = array(1, 'trunk');
$typeList      = array('unresolved', 'noclosed', 'assignedtome', 'openedbyme');
$paramList     = array(1);
$excludeBugs   = array(10);

$bug = new bugModelTest();
r($bug->getProjectBugsTest($projectIdList[0], 0, 'all'))                               && p('10:title;8:title;6:title;4:title;2:title') && e('BUG10;BUG8;BUG6;BUG4;BUG2'); // 测试获取项目ID为2的bug
r($bug->getProjectBugsTest($projectIdList[0], 0, 'all', 0, 'all', 0, $excludeBugs[0])) && p('8:title;6:title;4:title;2:title')          && e('BUG8;BUG6;BUG4;BUG2');       // 测试获取项目ID为2不包含id为10的bug
r($bug->getProjectBugsTest($projectIdList[0], 0, 'all', $buildIdList[0]))              && p('9:title;6:title;3:title')                  && e('BUG9;BUG6;BUG3');            // 测试获取项目ID为2, 影响版本为1的bug
r($bug->getProjectBugsTest($projectIdList[0], $productIdList[1], 'all'))               && p('10:title;6:title;2:title')                 && e('BUG10;BUG6;BUG2');           // 测试获取项目ID为2,产品ID为2的bug
r($bug->getProjectBugsTest($projectIdList[0], $productIdList[1], $branchIdList[0]))    && p('6:title')                                  && e('BUG6');                      // 测试获取项目ID为2,产品ID为2, 分支为1的bug
r($bug->getProjectBugsTest($projectIdList[0], 0, 'all', 0, $typeList[0]))              && p('8:title;2:title')                          && e('BUG8;BUG2');                 // 测试获取项目ID为2,未解决的bug
r($bug->getProjectBugsTest($projectIdList[0], 0, 'all', 0, $typeList[1]))              && p('10:title;8:title;4:title;2:title')         && e('BUG10;BUG8;BUG4;BUG2');      // 测试获取项目ID为2,未关闭的bug
r($bug->getProjectBugsTest($projectIdList[1], 0, 'all', 0, 'all', $paramList[0]))      && p('9:title;7:title;5:title;3:title;1:title')  && e('BUG9;BUG7;BUG5;BUG3;BUG1');  // 测试获取项目ID为1,模块为1的bug
