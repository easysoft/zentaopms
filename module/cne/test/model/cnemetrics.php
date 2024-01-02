#!/usr/bin/env php
<?php

/**

title=测试 cneModel->cneMetrics();
timeout=0
cid=1

- 获取CNE平台的集群度量属性status @unknown

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$cneModel = $tester->loadModel('cne');

r($cneModel->cneMetrics()) && p('status') && e('unknown'); // 获取CNE平台的集群度量