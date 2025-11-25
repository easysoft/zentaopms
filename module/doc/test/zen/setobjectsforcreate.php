#!/usr/bin/env php
<?php

/**

title=测试 docZen::setObjectsForCreate();
timeout=0
cid=16224

- 测试项目类型linkType为project属性hasObjects @0
- 测试项目类型linkType为project且lib类型为execution属性hasExecution @1
- 测试linkType为execution属性hasObjects @0
- 测试linkType为product属性objectsCount @5
- 测试linkType为api属性objectsCount @5
- 测试linkType为mine属性hasObjects @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doclib')->loadYaml('setobjectsforcreate/doclib', false, 2)->gen(10);
zenData('product')->loadYaml('setobjectsforcreate/product', false, 2)->gen(5);
zenData('project')->loadYaml('setobjectsforcreate/project', false, 2)->gen(10);

su('admin');

$docTest = new docZenTest();

$lib1 = new stdClass();
$lib1->id = 1;
$lib1->type = 'project';
$lib1->execution = 0;

$lib2 = new stdClass();
$lib2->id = 2;
$lib2->type = 'execution';
$lib2->execution = 3;

$lib3 = new stdClass();
$lib3->id = 3;
$lib3->type = 'product';

$lib4 = new stdClass();
$lib4->id = 4;
$lib4->type = 'api';

r($docTest->setObjectsForCreateTest('project', $lib1, '', 1)) && p('hasObjects') && e('0'); // 测试项目类型linkType为project
r($docTest->setObjectsForCreateTest('project', $lib2, '', 2)) && p('hasExecution') && e('1'); // 测试项目类型linkType为project且lib类型为execution
r($docTest->setObjectsForCreateTest('execution', $lib2, '', 3)) && p('hasObjects') && e('0'); // 测试linkType为execution
r($docTest->setObjectsForCreateTest('product', $lib3, '', 1)) && p('objectsCount') && e('5'); // 测试linkType为product
r($docTest->setObjectsForCreateTest('api', $lib4, '', 1)) && p('objectsCount') && e('5'); // 测试linkType为api
r($docTest->setObjectsForCreateTest('mine', null, '', 0)) && p('hasObjects') && e('0'); // 测试linkType为mine