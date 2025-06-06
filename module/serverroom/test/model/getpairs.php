#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('serverroom')->loadYaml('serverroom')->gen(10);
su('admin');

/**

title=serverroomModel->getPairs();
timeout=0
cid=1

- 检查记录数。 @9
- 检查0和10的数据。
 -  @~~
 - 属性10 @北京 - 青云 - 机房10

*/

global $tester;
$roomModel = $tester->loadModel('serverroom');
$pairs     = $roomModel->getPairs();
r(count($pairs)) && p()       && e('9');                       // 检查记录数。
r($pairs)        && p('0,10') && e('~~,北京 - 青云 - 机房10'); // 检查0和10的数据。
