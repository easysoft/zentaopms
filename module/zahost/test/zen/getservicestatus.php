#!/usr/bin/env php
<?php

/**

title=测试 zahostZen::getServiceStatus();
timeout=0
cid=19759

- 执行zahostTest模块的getServiceStatusTest方法 属性kvm @not_install
- 执行zahostTest模块的getServiceStatusTest方法 属性nginx @not_install
- 执行zahostTest模块的getServiceStatusTest方法 属性novnc @not_install
- 执行zahostTest模块的getServiceStatusTest方法 属性websockify @not_install
- 执行zahostTest模块的getServiceStatusTest方法 属性kvm @not_install

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahostzen.unittest.class.php';

$host = zenData('host');
$host->id->range('1-10');
$host->name->range('host{1-10}');
$host->extranet->range('192.168.1.{1-10}');
$host->zap->range('8086');
$host->tokenSN->range('token{1-10}');
$host->status->range('wait,offline,online{5},ready{2}');
$host->type->range('zahost');
$host->deleted->range('0');
$host->gen(10);

su('admin');

$zahostTest = new zahostTest();

r($zahostTest->getServiceStatusTest((object)['status' => 'wait', 'extranet' => '192.168.1.1', 'zap' => '8086', 'tokenSN' => 'token1'])) && p('kvm') && e('not_install');
r($zahostTest->getServiceStatusTest((object)['status' => 'offline', 'extranet' => '192.168.1.2', 'zap' => '8086', 'tokenSN' => 'token2'])) && p('nginx') && e('not_install');
r($zahostTest->getServiceStatusTest((object)['status' => 'online', 'extranet' => 'invalid-host', 'zap' => '8086', 'tokenSN' => 'token3'])) && p('novnc') && e('not_install');
r($zahostTest->getServiceStatusTest((object)['status' => 'ready', 'extranet' => '192.168.1.4', 'zap' => '8086', 'tokenSN' => 'invalid-token'])) && p('websockify') && e('not_install');
r($zahostTest->getServiceStatusTest((object)['status' => 'online', 'extranet' => '192.168.1.5', 'zap' => '8086', 'tokenSN' => 'token5'])) && p('kvm') && e('not_install');