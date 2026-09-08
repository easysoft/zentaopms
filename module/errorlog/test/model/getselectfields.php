#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getSelectFields();
timeout=0
cid=0

- 执行errorlog模块的getSelectFieldsTest方法，字段串与预期一致 @1
- 获取的字段串包含7个t1前缀字段 @7
- 获取的字段串包含6个t2前缀字段 @6
- 获取的字段串不含通配符* @1
- 获取的字段串长度为135 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlogModel = new errorlogModelTest();
$fields        = $errorlogModel->getSelectFieldsTest();

r($fields === 't1.id, t1.requestID, t1.module, t1.method, t1.account, t1.url, t1.createdDate, t2.md5, t2.level, t2.message, t2.file, t2.line, t2.trace') && p() && e('1');
r(substr_count($fields, 't1.')) && p() && e('7');
r(substr_count($fields, 't2.')) && p() && e('6');
r(!str_contains($fields, '*')) && p() && e('1');
r(strlen($fields) === 135) && p() && e('1');
