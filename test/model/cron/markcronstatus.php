#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->markCronStatus();
cid=1
pid=1

修改配置项之后查看更新信息 >> stop
修改配置项之后查看更新信息 >> run

*/

$cron     = new cronTest();
$id       = $tester->cron->getConfigID();
$configID = $cron->markCronStatusTest('stop', $id);
$config1  = $tester->dao->select('*')->from(TABLE_CONFIG)->where( 'id')->eq($configID)->fetch();

$tester->dao->delete()->from(TABLE_CONFIG)->where( 'id')->eq($configID)->exec();
$configID = $cron->markCronStatusTest('run');
$config2  = $tester->dao->select('*')->from(TABLE_CONFIG)->where( 'id')->eq($configID)->fetch();

$tester->dao->update(TABLE_CONFIG)->set('id')->eq($id)->where( 'id')->eq($configID)->exec();

r($config1) && p('value') && e('stop'); //修改配置项之后查看更新信息
r($config2) && p('value') && e('run');  //修改配置项之后查看更新信息

