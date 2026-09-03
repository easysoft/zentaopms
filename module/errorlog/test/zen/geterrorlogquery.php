#!/usr/bin/env php
<?php

/**

title=测试 errorlogZen::getErrorLogQuery();
timeout=0
cid=0

- 无查询记录时返回默认条件 @ 1 = 1
- 按ID加载查询条件 @`level` = 1
- 查询后session保留上次条件 @`level` = 1
- 不存在的查询ID保留上次条件 @`level` = 1
- 使用自定义session名称时返回默认条件 @ 1 = 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$query = zenData('userquery');
$query->id->range('1-3');
$query->account->range('admin');
$query->module->range('errorlog');
$query->title->range('查询1');
$query->form->range('`a:0:{}`');
$query->sql->range('`level` = 1,`module` = \'bug\',1 = 1');
$query->gen(3);

su('admin');

helper::import($tester->app->getModulePath('', 'errorlog') . 'control.php');

$errorlogZen = new errorlogZenTest();

r($errorlogZen->getErrorLogQueryTest(0)) && p() && e(' 1 = 1'); // 无查询记录时返回默认条件
r($errorlogZen->getErrorLogQueryTest(1)) && p() && e('`level` = 1'); // 按ID加载查询条件
r($errorlogZen->getErrorLogQueryTest(0)) && p() && e('`level` = 1'); // 查询后session保留上次条件
r($errorlogZen->getErrorLogQueryTest(999999)) && p() && e('`level` = 1'); // 不存在的查询ID保留上次条件
r($errorlogZen->getErrorLogQueryTest(0, 'customErrorlogQuery')) && p() && e(' 1 = 1'); // 自定义session名称返回默认条件
