#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getLevelType();
timeout=0
cid=0

- E_ERROR级别返回danger @danger
- E_USER_ERROR级别返回danger @danger
- E_WARNING级别返回warning @warning
- E_USER_WARNING级别返回warning @warning
- 普通级别返回primary @primary

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getLevelTypeTest(E_ERROR)) && p() && e('danger'); // E_ERROR级别
r($errorlogModel->getLevelTypeTest(E_USER_ERROR)) && p() && e('danger'); // E_USER_ERROR级别
r($errorlogModel->getLevelTypeTest(E_WARNING)) && p() && e('warning'); // E_WARNING级别
r($errorlogModel->getLevelTypeTest(E_USER_WARNING)) && p() && e('warning'); // E_USER_WARNING级别
r($errorlogModel->getLevelTypeTest(0)) && p() && e('primary'); // 普通级别
