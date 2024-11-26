#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getPods();
timeout=0
cid=1

- 获取CNE平台的pod status @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneModel = new cneTest();

r($cneModel->getPodsTest()) && p('status') && e('normal');