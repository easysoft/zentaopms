#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addAdminInviteField();
cid=1

- 当projectID为空时,返回false @0
- 当sprintIDList为空时,返回false @0
- 当project和sprint都不为空时,正常执行并且返回true @1
- 当project和sprint都不为空时,正常执行并且返回true @1
- 判断模块的root值是否更新成功 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';
zdTable('module')->config('module')->gen(1);

$upgrade = new upgradeTest();

$programIDList = array(0, 1);
$projectIDList = array(0, 1);
$lineIDList    = array(0, 1);
$productIdList = array(array(), array(1));
$sprintIdList  = array(array(), array(1));

r($upgrade->processMergedData($programIDList[0], $projectIDList[1], $lineIDList[0], $productIdList[0], $sprintIdList[0])) && p('') && e(0);   //当projectID为空时,返回false
r($upgrade->processMergedData($programIDList[0], $projectIDList[0], $lineIDList[0], $productIdList[0], $sprintIdList[1])) && p('') && e(0);   //当sprintIDList为空时,返回false
r($upgrade->processMergedData($programIDList[0], $projectIDList[1], $lineIDList[0], $productIdList[1], $sprintIdList[1])) && p('') && e(1);   //当project和sprint都不为空时,正常执行并且返回true
r($upgrade->processMergedData($programIDList[0], $projectIDList[1], $lineIDList[1], $productIdList[1], $sprintIdList[1])) && p('') && e(1);   //当project和sprint都不为空时,正常执行并且返回true

global $tester;
$module = $tester->dao->select('*')->from(TABLE_MODULE)->where('id')->eq(1)->fetch(); 
r($module) && p('root') && e(1);    //判断模块的root值是否更新成功
