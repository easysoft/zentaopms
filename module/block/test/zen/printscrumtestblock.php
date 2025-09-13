#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumTestBlock();
timeout=0
cid=0

- 执行blockTest模块的printScrumTestBlockTest方法，参数是$defaultBlock 
 - 属性sessionSetCalled @4
 - 属性langLoaded @1
 - 属性testtaskCount @10
- 执行blockTest模块的printScrumTestBlockTest方法，参数是$doingBlock 
 - 属性blockType @doing
 - 属性testtaskCount @5
- 执行blockTest模块的printScrumTestBlockTest方法，参数是$waitBlock 
 - 属性blockType @wait
 - 属性testtaskCount @3
- 执行blockTest模块的printScrumTestBlockTest方法，参数是$doneBlock 
 - 属性blockType @done
 - 属性testtaskCount @7
- 执行blockTest模块的printScrumTestBlockTest方法，参数是$invalidBlock 
 - 属性blockType @invalid
 - 属性testtaskCount @0
- 执行blockTest模块的printScrumTestBlockTest方法，参数是$countBlock 
 - 属性blockCount @5
 - 属性testtaskCount @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zendata('testtask')->loadYaml('testtask', false, 2)->gen(15);
zendata('product')->loadYaml('product', false, 2)->gen(5);
zendata('build')->loadYaml('build', false, 2)->gen(5);
zendata('project')->loadYaml('project', false, 2)->gen(5);
zendata('projectproduct')->loadYaml('projectproduct', false, 2)->gen(10);

su('admin');

$blockTest = new blockTest();

// 创建测试用的block对象
$defaultBlock = new stdclass();
$defaultBlock->params = new stdclass();
$defaultBlock->params->type = 'all';
$defaultBlock->params->count = 10;

$doingBlock = new stdclass();
$doingBlock->params = new stdclass();
$doingBlock->params->type = 'doing';
$doingBlock->params->count = 10;

$waitBlock = new stdclass();
$waitBlock->params = new stdclass();
$waitBlock->params->type = 'wait';
$waitBlock->params->count = 10;

$doneBlock = new stdclass();
$doneBlock->params = new stdclass();
$doneBlock->params->type = 'done';
$doneBlock->params->count = 10;

$invalidBlock = new stdclass();
$invalidBlock->params = new stdclass();
$invalidBlock->params->type = 'invalid';
$invalidBlock->params->count = 10;

$countBlock = new stdclass();
$countBlock->params = new stdclass();
$countBlock->params->type = 'all';
$countBlock->params->count = 5;

r($blockTest->printScrumTestBlockTest($defaultBlock)) && p('sessionSetCalled,langLoaded,testtaskCount') && e('4,1,10');
r($blockTest->printScrumTestBlockTest($doingBlock)) && p('blockType,testtaskCount') && e('doing,5');
r($blockTest->printScrumTestBlockTest($waitBlock)) && p('blockType,testtaskCount') && e('wait,3');
r($blockTest->printScrumTestBlockTest($doneBlock)) && p('blockType,testtaskCount') && e('done,7');
r($blockTest->printScrumTestBlockTest($invalidBlock)) && p('blockType,testtaskCount') && e('invalid,0');
r($blockTest->printScrumTestBlockTest($countBlock)) && p('blockCount,testtaskCount') && e('5,10');