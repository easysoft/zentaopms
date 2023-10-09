#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->update();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

zdTable('release')->config('release')->gen(6);
zdTable('user')->gen(5);
su('admin');

$releases = array(1, 6, 0);
$today    = date('Y-m-d');

$normalRelease    = array('name' => '修改正常发布',     'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today);
$terminateRelease = array('name' => '修改停止维护发布', 'marker' => 1, 'build' => '11', 'status' => 'terminate', 'product' => 1 , 'branch' => 0, 'date' => $today);
$noReleaseID      = array('name' => '测试任务ID为空',   'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today);
$noName           = array('name' => '',                 'marker' => 0, 'build' => '1',  'status' => 'normal',    'product' => 1 , 'branch' => 0, 'date' => $today);

$releaseTester = new releaseTest();
r($releaseTester->updateTest($releases[0], $normalRelease))    && p('id,name,build') && e('1,修改正常发布,1');       //修改正常发布
r($releaseTester->updateTest($releases[1], $terminateRelease)) && p('id,name,build') && e('6,修改停止维护发布,11');  //修改停止维护发布
r($releaseTester->updateTest($releases[2], $noReleaseID))      && p()                && e('0');                      //任务ID为空测试
r($releaseTester->updateTest($releases[0], $noName))           && p('name:0')        && e('『发布名称』不能为空。'); //名称为空测试

