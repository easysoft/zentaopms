#!/usr/bin/env php
<?php

/**

title=测试 docModel->moveLib();
timeout=0
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->loadYaml('doclib')->gen(30);

global $tester;
$tester->loadModel('doc');

$mineLibID   = 11;
$customLibID = 6;

$emptyData  = new stdclass();
$customData = new stdclass();
$mineData   = new stdclass();
$errorData  = new stdclass();

$customData->space = 7;
$mineData->space   = 'mine';
$errorData->space  = 'project';
