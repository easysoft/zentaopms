#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumProductBlock();
timeout=0
cid=15290

- 执行blockTest模块的printScrumProductBlockTest方法，参数是$block1 属性productsCount @15
- 执行blockTest模块的printScrumProductBlockTest方法，参数是$block2 属性productsCount @5
- 执行blockTest模块的printScrumProductBlockTest方法，参数是$block3 属性productsCount @10
- 执行blockTest模块的printScrumProductBlockTest方法，参数是$block4 属性productsCount @20
- 执行blockTest模块的printScrumProductBlockTest方法，参数是$block5
 - 属性productsCount @15
 - 属性storiesCount @15
 - 属性bugsCount @15
 - 属性releasesCount @15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->program->range('1');
$product->name->range('`产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10,产品11,产品12,产品13,产品14,产品15,产品16,产品17,产品18,产品19,产品20`');
$product->deleted->range('0');
$product->gen(20);

$story = zenData('story');
$story->product->range('1-20');
$story->deleted->range('0');
$story->gen(60);

$bug = zenData('bug');
$bug->product->range('1-20');
$bug->deleted->range('0');
$bug->gen(40);

$release = zenData('release');
$release->product->range('1-20');
$release->deleted->range('0');
$release->gen(30);

global $tester;
$tester->session->set('program', 1);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->count = 15;
r($blockTest->printScrumProductBlockTest($block1)) && p('productsCount') && e('15');

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->count = 5;
r($blockTest->printScrumProductBlockTest($block2)) && p('productsCount') && e('5');

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->count = 10;
r($blockTest->printScrumProductBlockTest($block3)) && p('productsCount') && e('10');

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->count = 100;
r($blockTest->printScrumProductBlockTest($block4)) && p('productsCount') && e('20');

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->count = 15;
r($blockTest->printScrumProductBlockTest($block5)) && p('productsCount,storiesCount,bugsCount,releasesCount') && e('15,15,15,15');