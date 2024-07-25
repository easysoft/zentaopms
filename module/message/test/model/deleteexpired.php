#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

$notify = zenData('notify');
$notify->objectType->range('message');
$notify->toList->range('`,admin,`,`,user1,`');
$notify->status->range('wait,sended,read');
$notify->gen(10);

zenData('user')->gen(1);
