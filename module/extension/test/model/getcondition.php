#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getCondition();
timeout=0
cid=1

- 执行 @1
- 获取代号为code1的插件包配置信息。
 - 第zentao条的compatible属性 @~~
 - 第zentao条的incompatible属性 @~~
 - 属性depends @~~
 - 属性conflicts @~~

*/

global $tester;
$tester->loadModel('extension');

r(1) && p() && e('1');
r($tester->extension->getCondition('code1')) && p('zentao:compatible,incompatible;depends,conflicts') && e('~~,~~,~~,~~');  // 获取代号为code1的插件包配置信息。
