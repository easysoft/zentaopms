#!/usr/bin/env php
<?php

/**

title=测试 actionModel->renderChanges();
timeout=0
cid=14929

- 测试type为空 @0
- 测试bug解决方案历史记录 @修改了 <strong><i>解决方案</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />
- 测试bug解决版本历史记录 @修改了 <strong><i>解决版本</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />
- 测试bug解决日期历史记录 @修改了 <strong><i>解决日期</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />
- 测试产品状态历史记录 @修改了 <strong><i>状态</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');
zenData('action')->gen(10);

$history = zenData('history');
$history->old->range('oldValue');
$history->oldValue->range('oldValue');
$history->new->range('newValue');
$history->newValue->range('newValue');
$history->gen(10);

$action = new actionTest();
r($action->renderChangesTest(''))           && p('') && e('0'); // 测试type为空
r($action->renderChangesTest('bug',     1)) && p('') && e('修改了 <strong><i>解决方案</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />'); // 测试bug解决方案历史记录
r($action->renderChangesTest('bug',     2)) && p('') && e('修改了 <strong><i>解决版本</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />'); // 测试bug解决版本历史记录
r($action->renderChangesTest('bug',     3)) && p('') && e('修改了 <strong><i>解决日期</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />'); // 测试bug解决日期历史记录
r($action->renderChangesTest('product', 4)) && p('') && e('修改了 <strong><i>状态</i></strong>，旧值为 "oldValue"，新值为 "newValue"。<br />');     // 测试产品状态历史记录