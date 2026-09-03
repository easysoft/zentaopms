#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getModulePairs();
timeout=0
cid=0

- 获取有错误日志的模块数量 @2
- bug模块存在 @bug
- task模块存在 @task
- 不存在的模块不进入模块列表 @1
- 返回结果为关联数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{5}');
$errorlog->module->range('bug{2},task{3}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();
$modulePairs   = $errorlogModel->getModulePairsTest();

r(count($modulePairs)) && p() && e('2'); // 模块数量
r($modulePairs) && p('bug') && e('bug'); // bug模块
r($modulePairs) && p('task') && e('task'); // task模块
r(!isset($modulePairs['not-exists'])) && p() && e('1'); // 不存在的模块不进入模块列表
r(is_array($modulePairs)) && p() && e('1'); // 返回结果为关联数组
