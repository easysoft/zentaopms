#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getList();
timeout=0
cid=0

- 默认按ID倒序查询第一条的requestID属性 @req-002
- 默认按ID倒序查询第二条的requestID属性 @req-002
- 按module=bug查询第一条的requestID属性 @req-002
- 按module=bug查询第二条的requestID属性 @req-001
- 按module=task查询第一条的requestID属性 @req-002
- 按module=task查询的日志数量 @2
- 查询不存在模块返回空数组 @0
- 空查询条件按ID正序查询第一条的requestID属性 @req-001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->module->range('bug{3},task{2}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getListTest()) && p('0:requestID') && e('req-002'); // 默认倒序第一条
r($errorlogModel->getListTest()) && p('1:requestID') && e('req-002'); // 默认倒序第二条
r($errorlogModel->getListTest("`module` = 'bug'")) && p('0:requestID') && e('req-002'); // bug查询第一条
r($errorlogModel->getListTest("`module` = 'bug'")) && p('1:requestID') && e('req-001'); // bug查询第二条
r($errorlogModel->getListTest("`module` = 'task'")) && p('0:requestID') && e('req-002'); // task查询第一条
r(count($errorlogModel->getListTest("`module` = 'task'"))) && p() && e('2');
r(count($errorlogModel->getListTest("`module` = 'not-exists'"))) && p() && e('0');
r($errorlogModel->getListTest('', 'id_asc')) && p('0:requestID') && e('req-001'); // 空条件正序第一条
