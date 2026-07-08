#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::isClickable();
timeout=0
cid=0

- 执行repoTest模块的isClickableTest方法，参数是$repo1, 'enable'  @0
- 执行repoTest模块的isClickableTest方法，参数是$repo2, 'disable'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repo3, ''  @0
- 执行repoTest模块的isClickableTest方法，参数是$repo4, 'reportView'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repo5, 'edit'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$flowTest = new reporeviewflowTest();

$flow1 = new stdclass();
$flow1->status = 'disable';

$flow2 = new stdclass();
$flow2->status = '';

$flow3 = new stdclass();
$flow3->status = 'enable';

$flow4 = new stdclass();
$flow4->report = '';

$flow5 = new stdclass();

r($flowTest->isClickableTest($flow1, 'enable'))     && p() && e('1');
r($flowTest->isClickableTest($flow2, 'disable'))    && p() && e('0');
r($flowTest->isClickableTest($flow3, ''))           && p() && e('1');
r($flowTest->isClickableTest($flow4, 'reportView')) && p() && e('1');
r($flowTest->isClickableTest($flow5, 'edit'))       && p() && e('1');
