#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByID();
timeout=0
cid=0

- 正常删除ID为2的错误日志 @1
- 被删除的日志无法再查询到 @0
- 删除后剩余2条错误日志 @2
- ID为0时不删除任何日志 @1
- 不存在的ID不报错 @1
- 删除ID为0和不存在ID后日志数量不变 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{3}');
$errorlog->gen(3);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByIDTest(2)) && p() && e('1'); // 正常删除ID为2的错误日志
r($errorlogModel->getByIDTest(2)) && p() && e('0'); // 被删除的日志无法再查询到
r(count($errorlogModel->getListTest())) && p() && e('2'); // 删除后剩余2条错误日志
r($errorlogModel->deleteByIDTest(0)) && p() && e('1'); // ID为0时不删除任何日志
r($errorlogModel->deleteByIDTest(999999)) && p() && e('1'); // 不存在的ID不报错
r(count($errorlogModel->getListTest())) && p() && e('2'); // 删除ID为0和不存在ID后日志数量不变
