#!/usr/bin/env php
<?php

/**

title=测试 apiZen::generateLibsDropMenu();
timeout=0
cid=15124

- 执行apiTest模块的generateLibsDropMenuTest方法，参数是$lib2, 0 属性text @正常产品2
- 执行apiTest模块的generateLibsDropMenuTest方法，参数是$lib6, 0 属性text @正常产品1
- 执行apiTest模块的generateLibsDropMenuTest方法，参数是$lib8, 0 属性text @项目集1
- 执行apiTest模块的generateLibsDropMenuTest方法，参数是$lib1, 0 属性text @独立接口
- 执行apiTest模块的generateLibsDropMenuTest方法，参数是$lib6, 1 属性link @generatelibsdropmenu.php?m=api&f=ajaxGetDropMenu&objectType=product&objectID=1&libID=6&version=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doclib')->loadYaml('generatelibsdropmenu/doclib', false, 2)->gen(10);
zenData('product')->loadYaml('generatelibsdropmenu/product', false, 2)->gen(5);
zenData('project')->loadYaml('generatelibsdropmenu/project', false, 2)->gen(5);

su('admin');

$apiTest = new apiZenTest();

$lib1 = new stdClass();
$lib1->id = 1;
$lib1->product = 0;
$lib1->project = 0;

$lib6 = new stdClass();
$lib6->id = 6;
$lib6->product = 1;
$lib6->project = 0;

$lib8 = new stdClass();
$lib8->id = 8;
$lib8->product = 0;
$lib8->project = 1;

$lib2 = new stdClass();
$lib2->id = 2;
$lib2->product = 2;
$lib2->project = 0;

r($apiTest->generateLibsDropMenuTest($lib2, 0)) && p('text') && e('正常产品2');
r($apiTest->generateLibsDropMenuTest($lib6, 0)) && p('text') && e('正常产品1');
r($apiTest->generateLibsDropMenuTest($lib8, 0)) && p('text') && e('项目集1');
r($apiTest->generateLibsDropMenuTest($lib1, 0)) && p('text') && e('独立接口');
r($apiTest->generateLibsDropMenuTest($lib6, 1)) && p('link') && e('generatelibsdropmenu.php?m=api&f=ajaxGetDropMenu&objectType=product&objectID=1&libID=6&version=1');