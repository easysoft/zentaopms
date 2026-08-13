#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getResponse();
timeout=0
cid=0

- 步骤 1：包含 listArgs 时返回 pager.total 为 10 @10
- 步骤 2：不包含 listArgs 时返回 data.name @success-value
- 步骤 3：传入 null 时返回 false @0
- 步骤 4：失败响应时返回 false @0
- 步骤 5：成功响应返回值类型为 object @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();

$withListArgs = (object)array('code' => 'success', 'data' => array((object)array('k' => 'v')), 'listArgs' => (object)array('total' => 10));
$noListArgs   = (object)array('code' => 'success', 'data' => (object)array('name' => 'success-value'));
$failMessage  = (object)array('code' => 'fail', 'message' => 'something wrong');

r($gitfoxTest->getResponseTest($withListArgs)) && p('pager:total') && e('10');
r($gitfoxTest->getResponseTest($noListArgs)) && p('name') && e('success-value');
r($gitfoxTest->getResponseTest(null)) && p() && e('0');
r($gitfoxTest->getResponseTest($failMessage)) && p() && e('0');
r($gitfoxTest->getResponseTypeTest($withListArgs)) && p() && e('object');
