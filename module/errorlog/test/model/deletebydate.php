#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByDate();
timeout=0
cid=0

- 正常删除截止日期之前的错误日志 @1
- 删除后剩余2条错误日志 @2
- 边界日期等于最后一条日志日期时不删除该日志 @1
- 边界日期仍保留1条错误日志 @1
- 边界日期加1秒后删除剩余到期日志 @1
- 删除后剩余0条错误日志 @0
- 未来日期删除全部日志 @1
- 再次执行删除无数据时不报错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->createdDate->range('20260101 000000-20260105 000000:1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByDateTest('2026-01-03 00:00:00')) && p() && e('1'); // 正常删除截止日期之前的错误日志
r(count($errorlogModel->getListTest())) && p() && e('2'); // 删除后剩余2条错误日志
r($errorlogModel->deleteByDateTest('2026-01-04 00:00:00')) && p() && e('1'); // 边界日期等于日志日期时不删除该日志
r(count($errorlogModel->getListTest())) && p() && e('1'); // 边界日期仍保留1条错误日志
r($errorlogModel->deleteByDateTest('2026-01-04 00:00:01')) && p() && e('1'); // 边界日期加1秒后删除到期日志
r(count($errorlogModel->getListTest())) && p() && e('0'); // 删除后剩余0条错误日志
r($errorlogModel->deleteByDateTest('2099-01-01 00:00:00')) && p() && e('1'); // 未来日期删除全部日志
r($errorlogModel->deleteByDateTest('2099-01-01 00:00:00')) && p() && e('1'); // 再次执行删除无数据时不报错
