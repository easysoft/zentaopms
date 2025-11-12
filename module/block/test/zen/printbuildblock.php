#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBuildBlock();
timeout=0
cid=0

- 执行blockTest模块的printBuildBlockTest方法，参数是$block1 属性count @0
- 执行blockTest模块的printBuildBlockTest方法，参数是$block2 属性count @0
- 执行blockTest模块的printBuildBlockTest方法，参数是$block3 属性count @0
- 执行blockTest模块的printBuildBlockTest方法，参数是$block4 属性count @0
- 执行blockTest模块的printBuildBlockTest方法，参数是$block5 属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$build = zenData('build');
$build->id->range('1-20');
$build->project->range('1-10');
$build->product->range('1-10');
$build->execution->range('1-20');
$build->name->range('Build `1-20`');
$build->deleted->range('0');
$build->gen(20);

zenData('product')->loadYaml('product')->gen(10);
zenData('project')->loadYaml('project')->gen(10);
zenData('user')->loadYaml('user')->gen(5);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->dashboard = 'my';
$block1->params = new stdClass();
$block1->params->count = 15;

$block2 = new stdClass();
$block2->dashboard = 'my';
$block2->params = new stdClass();
$block2->params->count = 10;

$block3 = new stdClass();
$block3->dashboard = 'my';
$block3->params = new stdClass();
$block3->params->count = 15;

$block4 = new stdClass();
$block4->dashboard = 'my';
$block4->params = new stdClass();
$block4->params->count = 15;

$block5 = new stdClass();
$block5->dashboard = 'project';
$block5->params = new stdClass();
$block5->params->count = 15;

r($blockTest->printBuildBlockTest($block1)) && p('count') && e('0');
r($blockTest->printBuildBlockTest($block2)) && p('count') && e('0');
su('user1');
r($blockTest->printBuildBlockTest($block3)) && p('count') && e('0');
su('admin');
r($blockTest->printBuildBlockTest($block4)) && p('count') && e('0');
global $tester;
$tester->session->set('project', 1);
r($blockTest->printBuildBlockTest($block5)) && p('count') && e('0');