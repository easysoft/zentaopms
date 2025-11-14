#!/usr/bin/env php
<?php

/**

title=测试 cneModel::certInfo();
timeout=0
cid=15606

- 步骤1：空证书名称查询 @0
- 步骤2：无效证书名称查询 @0
- 步骤3：有效证书名称查询第sans条的0属性 @devops.corp.cc
- 步骤4：使用默认channel参数查询第sans条的0属性 @devops.corp.cc
- 步骤5：有效证书名称带自定义channel查询第sans条的0属性 @devops.corp.cc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->certInfoTest('')) && p() && e('0');                               // 步骤1：空证书名称查询
r($cneTest->certInfoTest('invalid-cert-name')) && p() && e('0');             // 步骤2：无效证书名称查询
r($cneTest->certInfoTest('tls-haogs-cn')) && p('sans:0') && e('devops.corp.cc'); // 步骤3：有效证书名称查询
r($cneTest->certInfoTest('tls-haogs-cn', '')) && p('sans:0') && e('devops.corp.cc'); // 步骤4：使用默认channel参数查询
r($cneTest->certInfoTest('tls-haogs-cn', 'stable')) && p('sans:0') && e('devops.corp.cc'); // 步骤5：有效证书名称带自定义channel查询