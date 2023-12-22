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

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screen = new screenTest();

$component1 = new stdclass();
$component1->option = new stdclass();

$chart1 = new stdclass();
$chart1->name = 'testChart';

$screen->completeChartShowInfo($chart1, $component1);
r($component1->option) && p('title:text,left') && e('暂时没有数据,center');  //测试type=chart情况下，添加的容器默认属性是否正确
