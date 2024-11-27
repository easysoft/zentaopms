#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getEvents();
timeout=0
cid=1

- 获取CNE平台的事件 status @0
- 获取CNE平台的事件 status @0
- 获取CNE平台的事件 status @0
- 获取CNE平台的事件 status @0
- 获取CNE平台的事件 status @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneModel = new cneTest();

r($cneModel->getEventsTest()) && p('status') && e('0');
r($cneModel->getEventsTest()) && p('status') && e('0');
r($cneModel->getEventsTest()) && p('status') && e('0');
r($cneModel->getEventsTest()) && p('status') && e('0');
r($cneModel->getEventsTest()) && p('status') && e('0');