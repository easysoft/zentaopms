#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkPriv();
timeout=0
cid=18318

- 执行searchTest模块的checkPrivTest方法，参数是$results1, $objectPairs1  @3
- 执行searchTest模块的checkPrivTest方法，参数是$results2, $objectPairs2  @1
- 执行searchTest模块的checkPrivTest方法，参数是$results3, $objectPairs3  @0
- 执行searchTest模块的checkPrivTest方法，参数是$results4, $objectPairs4  @1
- 执行searchTest模块的checkPrivTest方法，参数是$results5, $objectPairs5  @0
- 执行searchTest模块的checkPrivTest方法，参数是$results6, array  @2
- 执行searchTest模块的checkPrivTest方法，参数是$results7, $objectPairs7  @2
- 执行searchTest模块的checkPrivTest方法，参数是$results8, $objectPairs8  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product 1,Product 2,Product 3,Product 4,Product 5,Product 6,Product 7,Product 8,Product 9,Product 10');
$product->shadow->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-20');
$project->name->range('Program 1-5,Project 6-10,Sprint 11-20');
$project->type->range('program{5},project{5},sprint{10}');
$project->gen(20);

$doc = zenData('doc');
$doc->id->range('1-5');
$doc->title->range('Doc 1,Doc 2,Doc 3,Doc 4,Doc 5');
$doc->lib->range('1,2,3,4,5');
$doc->deleted->range('0');
$doc->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('Lib 1,Lib 2,Lib 3,Lib 4,Lib 5');
$doclib->deleted->range('0');
$doclib->gen(5);

global $app;
$searchTest = new searchTaoTest();

$app->user->admin = true;
$results1 = array(
    1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'product', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'product', 'objectID' => 3)
);
$objectPairs1 = array('product' => array(1 => 1, 2 => 2, 3 => 3));

$app->user->admin = false;
$app->user->view = new stdClass();
$app->user->view->products = '1,2,3';
$app->user->view->programs = '1,2,3';
$app->user->view->projects = '6,7,8';
$app->user->view->sprints = '11,12,13';

$results2 = array(1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 1));
$objectPairs2 = array('product' => array(1 => 1));

$results3 = array(1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 5));
$objectPairs3 = array('product' => array(5 => 1));

$results4 = array(1 => (object)array('id' => 1, 'objectType' => 'project', 'objectID' => 6));
$objectPairs4 = array('project' => array(6 => 1));

$results5 = array(1 => (object)array('id' => 1, 'objectType' => 'execution', 'objectID' => 15));
$objectPairs5 = array('execution' => array(15 => 1));

$results6 = array(
    1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'product', 'objectID' => 2)
);

$results7 = array(
    1 => (object)array('id' => 1, 'objectType' => 'product', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'project', 'objectID' => 6)
);
$objectPairs7 = array('product' => array(1 => 1), 'project' => array(6 => 2));

$results8 = array(1 => (object)array('id' => 1, 'objectType' => 'unknown', 'objectID' => 1));
$objectPairs8 = array('unknown' => array(1 => 1));

r(count($searchTest->checkPrivTest($results1, $objectPairs1))) && p() && e('3');
r(count($searchTest->checkPrivTest($results2, $objectPairs2))) && p() && e('1');
r(count($searchTest->checkPrivTest($results3, $objectPairs3))) && p() && e('0');
r(count($searchTest->checkPrivTest($results4, $objectPairs4))) && p() && e('1');
r(count($searchTest->checkPrivTest($results5, $objectPairs5))) && p() && e('0');
r(count($searchTest->checkPrivTest($results6, array()))) && p() && e('2');
r(count($searchTest->checkPrivTest($results7, $objectPairs7))) && p() && e('2');
r(count($searchTest->checkPrivTest($results8, $objectPairs8))) && p() && e('1');