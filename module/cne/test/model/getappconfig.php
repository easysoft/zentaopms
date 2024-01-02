#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getAppConfig();
timeout=0
cid=1

- 获取第一个应用的配置信息
 - 第ingress条的host属性 @jflc.dops.corp.cc
 - 第min条的cpu属性 @1
- 获取第二个应用的配置信息
 - 第ingress条的host属性 @rheu.dops.corp.cc
 - 第min条的cpu属性 @0.5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();
r($cneModel->getAppConfigTest(1)) && p('ingress:host;min:cpu') && e('jflc.dops.corp.cc,1');   // 获取第一个应用的配置信息
r($cneModel->getAppConfigTest(2)) && p('ingress:host;min:cpu') && e('rheu.dops.corp.cc,0.5'); // 获取第二个应用的配置信息