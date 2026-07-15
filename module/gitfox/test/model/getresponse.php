#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getResponse();
timeout=0
cid=0

- 执行model模块的getResponse方法，参数是$withListArgs 第pager条的total属性 @10
- 执行model模块的getResponse方法，参数是$noListArgs 属性name @success-value
- 执行model模块的getResponse方法，参数是null  @0
- 执行model模块的getResponse方法，参数是$failMessage  @0
- 执行model模块的getResponse方法，参数是$withListArgs  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$withListArgs = (object)array('code' => 'success', 'data' => array((object)array('k' => 'v')), 'listArgs' => (object)array('total' => 10));
$noListArgs   = (object)array('code' => 'success', 'data' => (object)array('name' => 'success-value'));
$failMessage  = (object)array('code' => 'fail', 'message' => 'something wrong');

r($model->getResponse($withListArgs)) && p('pager:total') && e('10');
r($model->getResponse($noListArgs)) && p('name') && e('success-value');
r($model->getResponse(null)) && p() && e('0');
r($model->getResponse($failMessage)) && p() && e('0');
r(is_object($model->getResponse($withListArgs))) && p() && e('1');