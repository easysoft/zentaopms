#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('serverroom')->gen(10);

/**

title=serverroomModel->getPairs();
timeout=0
cid=1

*/

global $tester;
$roomModel = $tester->loadModel('serverroom');
$pairs     = $roomModel->getPairs();
r(count($pairs)) && p()       && e('11');                              // 检查记录数。
r($pairs)        && p('0,10') && e('``,北京 - 青云 - 这是机房名称10'); // 检查0和10的数据。
