#!/usr/bin/env php
<?php

/**

title=测试 cneModel::certInfo();
timeout=0
cid=0

- 步骤1：空证书名称查询 @0
- 步骤2：无效证书名称查询 @0
- 步骤3：有效证书名称查询第sans条的0属性 @devops.corp.cc
- 步骤4：自定义channel参数查询第sans条的0属性 @devops.corp.cc
- 步骤5：无效channel处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->certInfoTest('')) && p() && e('0');                               // 步骤1：空证书名称查询
r($cneTest->certInfoTest('invalid-cert-name')) && p() && e('0');             // 步骤2：无效证书名称查询
r($cneTest->certInfoTest('tls-haogs-cn')) && p('sans:0') && e('devops.corp.cc'); // 步骤3：有效证书名称查询
r($cneTest->certInfoWithChannelTest('tls-haogs-cn', 'stable')) && p('sans:0') && e('devops.corp.cc'); // 步骤4：自定义channel参数查询
r($cneTest->certInfoWithInvalidChannelTest('tls-haogs-cn', 'invalid-channel')) && p() && e('0'); // 步骤5：无效channel处理