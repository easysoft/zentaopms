#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/ci.class.php';
su('admin');

/**

title=测试 ciModel->updateBuildStatus();
cid=1
pid=1

更新构建任务状态为构建中 >> building
更新构建任务状态为成功 >> success
更新构建任务状态为失败 >> failure

*/

$ci = new ciTest();

r($ci->updateBuildStatusTest(1, 'building')) && p('status') && e('building'); //更新构建任务状态为构建中
r($ci->updateBuildStatusTest(1, 'success'))  && p('status') && e('success');  //更新构建任务状态为成功
r($ci->updateBuildStatusTest(1, 'failure'))  && p('status') && e('failure');  //更新构建任务状态为失败