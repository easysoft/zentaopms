#!/usr/bin/env php
<?php

/**

title=测试 cneModel::queryStatus();
timeout=0
cid=0

- 步骤1：正常查询有效实例ID属性code >> 期望返回状态码0
- 步骤2：查询不存在的实例ID >> 期望返回false
- 步骤3：查询无效实例ID(0) >> 期望返回false
- 步骤4：查询负数实例ID >> 期望返回false
- 步骤5：查询超出范围的实例ID >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->queryStatusTest(1)) && p('code') && e(0);
r($cneTest->queryStatusTest(999)) && p() && e(false);
r($cneTest->queryStatusTest(0)) && p() && e(false);
r($cneTest->queryStatusTest(-1)) && p() && e(false);
r($cneTest->queryStatusTest(100)) && p() && e(false);