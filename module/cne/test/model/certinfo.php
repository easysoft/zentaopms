#!/usr/bin/env php
<?php

/**

title=测试 cneModel->certInfo();
timeout=0
cid=1

- 证书名称为空 @0
- 证书名称错误 @0
- 证书名称正确第sans条的0属性 @dops.corp.cc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

$cneModel = new cneTest();

$certName = '';
r($cneModel->certInfoTest($certName)) && p() && e('0'); // 证书名称为空

$certName = 'fail-name';
r($cneModel->certInfoTest($certName)) && p() && e('0'); // 证书名称错误

$certName = 'tls-haogs-cn';
r($cneModel->certInfoTest($certName)) && p('sans:0') && e('dops.corp.cc'); // 证书名称正确