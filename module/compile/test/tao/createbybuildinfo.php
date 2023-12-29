#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel->createByBuildInfo().
timeout=0
cid=1

- 测试当buildTType为空的时候，判断创建的compile信息是否正确。
 - 属性id @1
 - 属性name @testName1
 - 属性job @1
 - 属性createdBy @guest
- 测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。 @1
- 测试当buildTType为gitlab的时候，判断创建的compile信息是否正确，是否与build内的信息一致。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('compile')->gen(0);
su('admin');

$compile = new compileTest();

$nameList = array('testName1', 'testName2', 'testName3');
$objectIDList = array(1, 2, 3);
$buildTypeList = array('', 'jenkins', 'gitlab');

$build1 = new stdclass();
$build1->queueId = 1;
$build1->result  = 'SUCCESS';
$build1->timestamp = time() * 1000;

$build2 = new stdclass();
$build2->id = 1;
$build2->status = 'SUCCESS';
$build2->created_at = date('Y-m-d H:i:s', strtotime('-1 days'));

$build3 = new stdclass();

$buildList = array($build3, $build1, $build2);

$compile->createByBuildInfo($nameList[0], $objectIDList[0], $buildList[0], $buildTypeList[0]);
$compileInfo = $tester->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq(1)->fetch();
r($compileInfo) && p('id,name,job,createdBy') && e('1,testName1,1,guest');  //测试当buildTType为空的时候，判断创建的compile信息是否正确。

$compile->createByBuildInfo($nameList[1], $objectIDList[1], $buildList[1], $buildTypeList[1]);
$compileInfo = $tester->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq(2)->fetch();
$condition = $compileInfo->name === 'testName2' && $compileInfo->job === 2 && $compileInfo->createdBy === 'guest' && $compileInfo->queue === 1 && $compileInfo->status === 'success';
r($condition) && p() && e(1);   //测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。

$compile->createByBuildInfo($nameList[2], $objectIDList[2], $buildList[2], $buildTypeList[2]);
$compileInfo = $tester->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq(3)->fetch();
$condition = $compileInfo->name === 'testName3' && $compileInfo->createdBy === 'guest' && $compileInfo->status === 'SUCCESS' && $compileInfo->queue === 1 && $compileInfo->job === 3;
r($condition) && p() && e(1);   //测试当buildTType为gitlab的时候，判断创建的compile信息是否正确，是否与build内的信息一致。
