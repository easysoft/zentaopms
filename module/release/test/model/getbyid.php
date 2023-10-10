#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getByID();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('release')->config('release')->gen(5);
zdTable('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);

global $tester;
$tester->loadModel('release');
r($tester->release->getByID($releases[0])) && p()       && e('0');     // 测试获取ID为0的release信息
r($tester->release->getByID($releases[1])) && p('name') && e('发布1'); // 测试获取ID为1的release信息
r($tester->release->getByID($releases[2])) && p()       && e('0');     // 测试获取ID不存在的release信息
