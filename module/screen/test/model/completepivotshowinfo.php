#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->completeComponentShowInfo();
timeout=0
cid=1

- 测试type=pivot情况下，添加的容器默认属性是否正确
 - 属性rowNum @1
 - 属性headerBGC @transparent
 - 属性oddRowBGC @transparent

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screen = new screenTest();

$component1 = new stdclass();
$component1->option = new stdclass();

$chart1 = new stdclass();
$chart1->name = 'testChart';

$screen->completePivotShowInfo($chart1, $component1);
r($component1->option) && p('rowNum,headerBGC,oddRowBGC') && e('1,transparent,transparent');    //测试type=pivot情况下，添加的容器默认属性是否正确