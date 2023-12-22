#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildChart();
timeout=0
cid=1

- 测试componet属性都为空的情况下，生成的默认值是否正确。
 - 第styles条的hueRotate属性 @0
 - 第styles条的saturate属性 @1
 - 第styles条的blendMode属性 @normal
 - 第status条的lock属性 @~~
 - 第status条的hide属性 @~~
 - 第request条的requestHttpType属性 @get
 - 第request条的requestIntervalUnit属性 @second
 - 第request条的requestParamsBodyType属性 @none
- 测试styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1
- 测试request有值的情况下，是否被修改。属性request @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screen = new screenTest();

$component1 = new stdclass();

$component2 = new stdclass();
$component2->styles = 1;

$component3 = new stdclass();
$component3->status = 1;

$component4 = new stdclass();
$component4->request = 1;

$screen->setComponentDefaults($component1);
r($component1) && p('styles:hueRotate,saturate,blendMode;status:lock,hide;request:requestHttpType,requestIntervalUnit,requestParamsBodyType') && e('0,1,normal;~~,~~;get,second,none');     //测试componet属性都为空的情况下，生成的默认值是否正确。

$screen->setComponentDefaults($component2);
r($component2) && p('styles') && e(1);     //测试styles有值的情况下，是否被修改。

$screen->setComponentDefaults($component3);
r($component3) && p('status') && e(1);     //测试status有值的情况下，是否被修改。

$screen->setComponentDefaults($component4);
r($component4) && p('request') && e(1);     //测试request有值的情况下，是否被修改。