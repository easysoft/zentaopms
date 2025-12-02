#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(200);

/**

title=instanceModel->getByID();
timeout=0
cid=16802

- 获取所有instance数量 @200
- 获取运行中的instance数量 @198
- 获取关闭的instance数量 @1
- 获取异常的instance数量 @1
- 获取不存在状态的instance数量 @0
*/

global $tester;
$instance = $tester->loadModel('instance');

r($instance->getInstanceCount())           && p() && e('200'); // 获取所有instance数量
r($instance->getInstanceCount('running'))  && p() && e('198'); // 获取运行中的instance数量
r($instance->getInstanceCount('stopped'))  && p() && e('1');   // 获取关闭的instance数量
r($instance->getInstanceCount('abnormal')) && p() && e('1');   // 获取异常的instance数量
r($instance->getInstanceCount('test'))     && p() && e('0');   // 获取不存在状态的instance数量
