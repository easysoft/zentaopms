#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getByID();
timeout=0
cid=17988

- 测试获取ID为0的release信息 @0
- 测试获取ID不存在的release信息 @0
- 测试获取ID为1的名称信息属性name @发布1
- 测试获取ID为1的产品名称信息属性productName @正常产品1
- 测试获取ID为1的状态信息属性status @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(5);
zenData('release')->loadYaml('release')->gen(5);
zenData('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);

global $tester;
$tester->loadModel('release');
r($tester->release->getByID($releases[0])) && p()              && e('0');         // 测试获取ID为0的release信息
r($tester->release->getByID($releases[2])) && p()              && e('0');         // 测试获取ID不存在的release信息
r($tester->release->getByID($releases[1])) && p('name')        && e('发布1');     // 测试获取ID为1的名称信息
r($tester->release->getByID($releases[1])) && p('productName') && e('正常产品1'); // 测试获取ID为1的产品名称信息
r($tester->release->getByID($releases[1])) && p('status')      && e('normal');    // 测试获取ID为1的状态信息
