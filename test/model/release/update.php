#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->update();
cid=1
pid=1

修改正常发布 >> 1,修改正常发布,1
修改停止维护发布 >> 6,修改停止维护发布,11
任务ID为空测试 >> 0
名称为空测试 >> 『发布名称』不能为空。

*/
$releaseID = array('1', '6', '');

$release = new releaseTest();

$normalRelease    = array('name' => '修改正常发布',     'marker' => '0', 'build' => '1',  'status' => 'normal',    'product' => '1' );
$terminateRelease = array('name' => '修改停止维护发布', 'marker' => '1', 'build' => '11', 'status' => 'terminate', 'product' => '1' );
$noReleaseID      = array('name' => '测试任务ID为空',   'marker' => '0', 'build' => '1',  'status' => 'normal',    'product' => '1' );
$noName           = array('name' => '',                 'marker' => '0', 'build' => '1',  'status' => 'normal',    'product' => '1' );

r($release->updateTest($releaseID[0], $normalRelease))    && p('id,name,build') && e('1,修改正常发布,1');       //修改正常发布
r($release->updateTest($releaseID[1], $terminateRelease)) && p('id,name,build') && e('6,修改停止维护发布,11');  //修改停止维护发布
r($release->updateTest($releaseID[2], $noReleaseID))      && p()                && e('0');                      //任务ID为空测试
r($release->updateTest($releaseID[0], $noName))           && p('name:0')        && e('『发布名称』不能为空。'); //名称为空测试

