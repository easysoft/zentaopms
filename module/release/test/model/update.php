#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->update();
timeout=0
cid=18017

- 修改正常发布
 - 属性id @1
 - 属性name @修改正常发布
 - 属性build @1
- 修改停止维护发布
 - 属性id @6
 - 属性name @修改停止维护发布
 - 属性build @11
- 修改发布为wait第releasedDate条的0属性 @『实际发布日期』不能为空。
- 任务ID为空测试 @0
- 名称为空测试第name条的0属性 @『应用版本号』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

zenData('release')->loadYaml('release')->gen(6);
zenData('user')->gen(5);
su('admin');

$releases = array(1, 6, 0);
$today    = date('Y-m-d');

$normalRelease    = array('name' => '修改正常发布',     'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today, 'system' => '0');
$terminateRelease = array('name' => '修改停止维护发布', 'marker' => 1, 'build' => '11', 'status' => 'terminate', 'product' => 1 , 'branch' => 0, 'date' => $today, 'system' => '0');
$waitRelease      = array('name' => '修改发布为wait',   'marker' => 0, 'build' => '1',  'status' => 'wait',      'product' => 1 , 'branch' => 0, 'date' => $today, 'system' => '0');
$noReleaseID      = array('name' => '测试任务ID为空',   'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today, 'system' => '0');
$noName           = array('name' => '',                 'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today, 'system' => '0');

$releaseTester = new releaseTest();
r($releaseTester->updateTest($releases[0], $normalRelease))    && p('id,name,build')  && e('1,修改正常发布,1');           //修改正常发布
r($releaseTester->updateTest($releases[1], $terminateRelease)) && p('id,name,build')  && e('6,修改停止维护发布,11');      //修改停止维护发布
r($releaseTester->updateTest($releases[0], $waitRelease))      && p('releasedDate:0') && e('『实际发布日期』不能为空。'); //修改发布为wait
r($releaseTester->updateTest($releases[2], $noReleaseID))      && p()                 && e('0');                          //任务ID为空测试
r($releaseTester->updateTest($releases[0], $noName))           && p('name:0')         && e('『应用版本号』不能为空。');   //名称为空测试
