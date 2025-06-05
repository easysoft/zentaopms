#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel->createByBuildInfo().
timeout=0
cid=1

- 测试当buildTType为空的时候，判断创建的compile信息是否正确。 @0
- 测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。
 - 属性name @testName2
 - 属性job @2
 - 属性createdBy @admin
 - 属性queue @1
 - 属性status @success
- 测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。
 - 属性name @testName3
 - 属性job @3
 - 属性createdBy @admin
 - 属性queue @1
 - 属性status @failure

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

zenData('compile')->gen(0);
zenData('user')->gen(10);
su('admin');

$compile = new compileTest();

$nameList = array('testName1', 'testName2', 'testName3');
$objectIDList = array(1, 2, 3);
$buildTypeList = array('', 'jenkins', 'gitlab');

$build1 = new stdclass();
$build1->number = 1;
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
r($compileInfo) && p() && e('0');  //测试当buildTType为空的时候，判断创建的compile信息是否正确。

$compile->createByBuildInfo($nameList[1], $objectIDList[1], $buildList[1], $buildTypeList[1]);
$compileInfo = $tester->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq(1)->fetch();
r($compileInfo) && p('name,job,createdBy,queue,status') && e('testName2,2,system,1,success');   //测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。

$compile->createByBuildInfo($nameList[2], $objectIDList[2], $buildList[2], $buildTypeList[2]);
$compileInfo = $tester->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq(2)->fetch();
r($compileInfo) && p('name,job,createdBy,queue,status') && e('testName3,3,system,1,failure');   //测试当buildTType为jenkins的时候，判断创建的compile信息是否正确，是否与build内的信息一致。
