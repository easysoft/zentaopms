#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocMyCollectionBlock();
timeout=0
cid=0

- 执行blockTest模块的printDocMyCollectionBlockTest方法 属性success @1
- 执行blockTest模块的printDocMyCollectionBlockTest方法 
 - 属性docCount @5
- 执行blockTest模块的printDocMyCollectionBlockTest方法 
 - 属性docCount @0
- 执行blockTest模块的printDocMyCollectionBlockTest方法 属性docList @array
- 执行blockTest模块的printDocMyCollectionBlockTest方法 属性libList @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

$docTable = zenData('doc');
$docTable->id->range('1-10');
$docTable->title->range('我关注的文档{10}');
$docTable->status->range('normal{8},deleted{2}');
$docTable->lib->range('1-5');
$docTable->editedDate->range('2024-01-01 10:00:00,2024-01-02 11:00:00,2024-01-03 12:00:00');
$docTable->deleted->range('0{8},1{2}');
$docTable->gen(10);

$doclibTable = zenData('doclib');
$doclibTable->id->range('1-5');
$doclibTable->name->range('文档库{5}');
$doclibTable->deleted->range('0{5}');
$doclibTable->gen(5);

$docactionTable = zenData('docaction');
$docactionTable->id->range('1-8');
$docactionTable->doc->range('1-8');
$docactionTable->action->range('collect{8}');
$docactionTable->actor->range('admin{5},user1{3}');
$docactionTable->date->range('2024-01-01 09:00:00,2024-01-02 09:00:00,2024-01-03 09:00:00');
$docactionTable->gen(8);

su('admin');

$blockTest = new blockTest();

r($blockTest->printDocMyCollectionBlockTest()) && p('success') && e('1');
r($blockTest->printDocMyCollectionBlockTest()) && p('docCount') && e('5,<=');
r($blockTest->printDocMyCollectionBlockTest()) && p('docCount') && e('0,>=');
r($blockTest->printDocMyCollectionBlockTest()) && p('docList') && e('array');
r($blockTest->printDocMyCollectionBlockTest()) && p('libList') && e('array');