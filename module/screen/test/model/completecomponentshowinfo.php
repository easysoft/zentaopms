#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->completeComponentShowInfo();
timeout=0
cid=1

- 测试type=chart情况下，添加的容器默认属性是否正确
 - 第title条的text属性 @暂时没有数据
 - 第title条的left属性 @center
- 测试type=pivot情况下，添加的容器默认属性是否正确
 - 属性rowNum @1
 - 属性headerBGC @transparent
 - 属性oddRowBGC @transparent

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screen = new screenTest();

$typeList = array('chart', 'pivot');

$component1 = new stdclass();
$component2 = new stdclass();

$chart1 = new stdclass();
$chart2 = new stdclass();

$chart1->name = 'testChart';
$chart2->name = 'testChart2';

r($screen->completeComponentShowInfo($chart1, $component1, $typeList[0]));
r($component1->option) && p('title:text,left') && e('暂时没有数据,center');  //测试type=chart情况下，添加的容器默认属性是否正确

r($screen->completeComponentShowInfo($chart2, $component2, $typeList[1]));
r($component2->option) && p('rowNum,headerBGC,oddRowBGC') && e('1,transparent,transparent');    //测试type=pivot情况下，添加的容器默认属性是否正确
