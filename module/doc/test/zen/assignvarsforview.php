#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForView();
timeout=0
cid=16182

- 执行docTest模块的assignVarsForViewTest方法，参数是1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown
 - 属性docID @1
 - 属性type @product
- 执行docTest模块的assignVarsForViewTest方法，参数是2, 1, 'project', 2, 2, $doc2, $object2, 'project', $libs, $objectDropdown
 - 属性objectID @2
 - 属性libID @2
- 执行docTest模块的assignVarsForViewTest方法，参数是1, 2, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown
 - 属性version @2
 - 属性objectType @product
- 执行docTest模块的assignVarsForViewTest方法，参数是2, 0, 'project', 2, 2, $doc2, $object2, 'project', $libs, $objectDropdown
 - 属性spaceType @project
 - 属性moduleID @3
- 执行docTest模块的assignVarsForViewTest方法，参数是1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown
 - 属性productID @1
 - 属性projectID @1
 - 属性executionID @0
- 执行docTest模块的assignVarsForViewTest方法，参数是2, 0, 'execution', 2, 2, $doc2, $object2, 'execution', $libs, $objectDropdown
 - 属性objectType @execution
 - 属性objectID @2
- 执行docTest模块的assignVarsForViewTest方法，参数是1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown
 - 属性hasDoc @1
 - 属性hasObject @1
 - 属性hasLibs @1
 - 属性hasUsers @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zendata('doc')->gen(10);
zendata('doclib')->gen(10);
zendata('user')->gen(10);
zendata('product')->gen(5);
zendata('project')->gen(5);
zendata('action')->gen(20);

su('admin');

$docTest = new docZenTest();

global $tester;

$doc1 = new stdclass();
$doc1->id = 1;
$doc1->title = 'Test Document 1';
$doc1->module = 0;
$doc1->product = 1;
$doc1->project = 1;
$doc1->execution = 0;

$doc2 = new stdclass();
$doc2->id = 2;
$doc2->title = 'Test Document 2';
$doc2->module = 3;
$doc2->product = 2;
$doc2->project = 2;
$doc2->execution = 1;

$object1 = new stdclass();
$object1->id = 1;
$object1->project = 0;

$object2 = new stdclass();
$object2->id = 2;
$object2->project = 1;

$lib1 = new stdclass();
$lib1->id = 1;
$lib1->name = 'testlib';
$lib1->type = 'product';

$lib2 = new stdclass();
$lib2->id = 2;
$lib2->name = 'projectlib';
$lib2->type = 'project';

$libs = array(1 => $lib1, 2 => $lib2);
$objectDropdown = array(1 => 'Product 1', 2 => 'Product 2');

r($docTest->assignVarsForViewTest(1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown)) && p('docID,type') && e('1,product');
r($docTest->assignVarsForViewTest(2, 1, 'project', 2, 2, $doc2, $object2, 'project', $libs, $objectDropdown)) && p('objectID,libID') && e('2,2');
r($docTest->assignVarsForViewTest(1, 2, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown)) && p('version,objectType') && e('2,product');
r($docTest->assignVarsForViewTest(2, 0, 'project', 2, 2, $doc2, $object2, 'project', $libs, $objectDropdown)) && p('spaceType,moduleID') && e('project,3');
r($docTest->assignVarsForViewTest(1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown)) && p('productID,projectID,executionID') && e('1,1,0');
r($docTest->assignVarsForViewTest(2, 0, 'execution', 2, 2, $doc2, $object2, 'execution', $libs, $objectDropdown)) && p('objectType,objectID') && e('execution,2');
r($docTest->assignVarsForViewTest(1, 0, 'product', 1, 1, $doc1, $object1, 'product', $libs, $objectDropdown)) && p('hasDoc,hasObject,hasLibs,hasUsers') && e('1,1,1,1');