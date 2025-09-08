#!/usr/bin/env php
<?php

/**

title=Test metricModel::getLogFile();
timeout=0
cid=0

- Step 1: Normal case verify contains metriclib @*metriclib*
- Step 2: Verify ends with .log.php @*.log.php
- Step 3: Verify contains tmp and log directory @*/tmp/*log/*
- Step 4: Verify contains current date @*metriclib.20250908.log.php
- Step 5: Verify returns non-empty string @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->getLogFileTest()) && p() && e('/repo/zentaopms/tmp/log/metriclib.20250908.log.php');
r($metricTest->getLogFileTest()) && p() && e('/repo/zentaopms/tmp/log/metriclib.20250908.log.php');
r($metricTest->getLogFileTest()) && p() && e('/repo/zentaopms/tmp/log/metriclib.20250908.log.php');
r($metricTest->getLogFileTest()) && p() && e('/repo/zentaopms/tmp/log/metriclib.20250908.log.php');
r(strlen($metricTest->getLogFileTest()) > 0) && p() && e('1');