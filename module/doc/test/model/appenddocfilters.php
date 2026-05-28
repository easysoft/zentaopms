#!/usr/bin/env php
<?php

/**

title=测试 docModel->appendDocFilters();
timeout=0
cid=16191

- browseType=all结果数量 @4
- browseType=draft结果数量 @1
- browseType=draft第3条的title属性 @草稿文档
- filterType=createdByMe结果数量 @3
- filterType=editedByMe结果数量 @3
- hasPrivDocIdList结果数量 @2
- hasPrivDocIdList文档1title属性 @文档1
- filterDocs结果数量 @2
- filterDocs文档3title属性 @草稿文档
- appendDocs追加正常文档1结果数量 @2
- appendDocs追加正常文档1第1条的title属性 @文档1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$docTable = zenData('doc');
$docTable->id->range('1-4');
$docTable->title->range('文档1,文档2,草稿文档,文档4');
$docTable->status->range('normal,normal,draft,normal');
$docTable->addedBy->range('admin,user1,admin,admin');
$docTable->editedBy->range('admin,admin,admin,user1');
$docTable->vision->range('rnd');
$docTable->deleted->range('0');
$docTable->type->range('text');
$docTable->gen(4);

$docTest = new docModelTest();

function fetchAll($docTest, $browseType = 'all', $filterType = '', $hasPrivDocIdList = array(), $filterDocs = '', $appendDocs = '')
{
    $dao = $docTest->instance->dao->select('t1.*')->from(TABLE_DOC)->alias('t1')
        ->where('t1.deleted')->eq(0)
        ->andWhere('t1.vision')->eq('rnd');
    $dao = $docTest->appendDocFiltersTest($dao, $browseType, '', $filterType, $hasPrivDocIdList, $filterDocs, $appendDocs, 'id_asc');
    return $dao->fetchAll('id');
}

$result1 = fetchAll($docTest, 'all');
r(count($result1)) && p('结果数量') && e('4');

$result2 = fetchAll($docTest, 'draft');
r(count($result2)) && p('结果数量') && e('1');
r($result2) && p('3:title') && e('草稿文档');

$result3 = fetchAll($docTest, 'all', 'createdByMe');
r(count($result3)) && p('结果数量') && e('3');

$result4 = fetchAll($docTest, 'all', 'editedByMe');
r(count($result4)) && p('结果数量') && e('3');

$result5 = fetchAll($docTest, 'all', '', array(1, 2));
r(count($result5)) && p('结果数量') && e('2');
r($result5) && p('1:title') && e('文档1');

$result6 = fetchAll($docTest, 'all', '', array(), '1,2');
r(count($result6)) && p('结果数量') && e('2');
r($result6) && p('3:title') && e('草稿文档');

$result7 = fetchAll($docTest, 'draft', '', array(), '', '1');
r(count($result7)) && p('结果数量') && e('2');
r($result7) && p('1:title') && e('文档1');
