#!/usr/bin/env php
<?php

/**

title=测试 docTao::filterDeletedDocs();
timeout=0
cid=16168

- 执行docTest模块的filterDeletedDocsTest方法，参数是$normalDocs 第1条的title属性 @Document1
- 执行docTest模块的filterDeletedDocsTest方法，参数是$mixedDocs 第1条的title属性 @Document1
- 执行docTest模块的filterDeletedDocsTest方法，参数是$parentDeletedDocs 第4条的title属性 @Separate
- 执行docTest模块的filterDeletedDocsTest方法，参数是$countDocs  @2
- 执行docTest模块的filterDeletedDocsTest方法，参数是$complexDocs 第1条的title属性 @Root1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// zendata数据准备（即使不需要，也保持一致性）
$table = zenData('doc');
$table->id->range('1-10');
$table->title->range('Document1,Document2,Document3,Document4,Document5,Document6,Document7,Document8,Document9,Document10');
$table->deleted->range('0{7},1{3}');
$table->path->range(',1,1,2,1,2,3,1,2,3,4,1,2,1,2,3,1,2,3,4,5,1,2,3,1,2,3,4,1,2,3,4,5,6');
$table->lib->range('1-3');
$table->vision->range('rnd');
$table->gen(10);

su('admin');

$docTest = new docTest();

// 测试步骤1：正常文档过滤
$normalDocs = array(
    1 => (object)array('id' => 1, 'title' => 'Document1', 'deleted' => '0', 'path' => ',1,'),
    2 => (object)array('id' => 2, 'title' => 'Document2', 'deleted' => '0', 'path' => ',1,2,'),
    3 => (object)array('id' => 3, 'title' => 'Document3', 'deleted' => '0', 'path' => ',1,2,3,'),
);

// 测试步骤2：包含删除文档的情况
$mixedDocs = array(
    1 => (object)array('id' => 1, 'title' => 'Document1', 'deleted' => '0', 'path' => ',1,'),
    2 => (object)array('id' => 2, 'title' => 'Document2', 'deleted' => '1', 'path' => ',1,2,'),
    3 => (object)array('id' => 3, 'title' => 'Document3', 'deleted' => '0', 'path' => ',1,3,'),
);

// 测试步骤3：父文档删除影响子文档的情况
$parentDeletedDocs = array(
    1 => (object)array('id' => 1, 'title' => 'Parent', 'deleted' => '1', 'path' => ',1,'),
    2 => (object)array('id' => 2, 'title' => 'Child1', 'deleted' => '0', 'path' => ',1,2,'),
    3 => (object)array('id' => 3, 'title' => 'Child2', 'deleted' => '0', 'path' => ',1,3,'),
    4 => (object)array('id' => 4, 'title' => 'Separate', 'deleted' => '0', 'path' => ',4,'),
);

// 测试步骤4：计数测试
$countDocs = array(
    1 => (object)array('id' => 1, 'title' => 'Document1', 'deleted' => '0', 'path' => ',1,'),
    2 => (object)array('id' => 2, 'title' => 'Document2', 'deleted' => '0', 'path' => ',1,2,'),
);

// 测试步骤5：复杂情况
$complexDocs = array(
    1 => (object)array('id' => 1, 'title' => 'Root1', 'deleted' => '0', 'path' => ',1,'),
    2 => (object)array('id' => 2, 'title' => 'Deleted', 'deleted' => '1', 'path' => ',1,2,'),
    3 => (object)array('id' => 3, 'title' => 'Child1', 'deleted' => '0', 'path' => ',1,2,3,'),
    4 => (object)array('id' => 4, 'title' => 'Child2', 'deleted' => '0', 'path' => ',1,2,4,'),
    5 => (object)array('id' => 5, 'title' => 'Root2', 'deleted' => '0', 'path' => ',5,'),
    6 => (object)array('id' => 6, 'title' => 'Independent', 'deleted' => '0', 'path' => ',6,'),
);

r($docTest->filterDeletedDocsTest($normalDocs)) && p('1:title') && e('Document1');
r($docTest->filterDeletedDocsTest($mixedDocs)) && p('1:title') && e('Document1');
r($docTest->filterDeletedDocsTest($parentDeletedDocs)) && p('4:title') && e('Separate');
r(count($docTest->filterDeletedDocsTest($countDocs))) && p() && e('2');
r($docTest->filterDeletedDocsTest($complexDocs)) && p('1:title') && e('Root1');