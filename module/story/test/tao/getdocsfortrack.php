#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getDocsForTrack();
timeout=0
cid=0

- 执行storyTao模块的getDocsForTrack方法，参数是array  @0
- 执行storyTao模块的getDocsForTrack方法，参数是array  @0
- 执行$docs[1] @1
- 执行$docs[2] @2
- 执行$docs[1][1]
 - 属性id @1
 - 属性title @文档1
 - 属性addedBy @admin
 - 属性AID @1
- 执行$docs[2][2]
 - 属性id @2
 - 属性title @文档2
 - 属性addedBy @admin
 - 属性AID @2

*/


include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$user = zenData('user');
$user->account->range('admin,user1');
$user->realname->range('管理员,用户1');
$user->gen(2);

$relation = zenData('relation');
$relation->AID->range('1-4');
$relation->AType->range('story');
$relation->relation->range('1');
$relation->BID->range('1-4');
$relation->BType->range('doc');
$relation->gen(4);

$doc = zenData('doc');
$doc->lib->range('1{4}');
$doc->product->range('1{4}');
$doc->title->range('文档1,文档2,文档3,文档4');
$doc->addedBy->range('admin{4}');
$doc->type->range('text{4}');
$doc->status->range('normal{4}');
$doc->deleted->range('0,0,0,1');
$doc->gen(4);

global $tester;
$storyTao = $tester->loadModel('story');

su('admin');
r(count($storyTao->getDocsForTrack(array()))) && p() && e('0');
r(count($storyTao->getDocsForTrack(array(99)))) && p() && e('0');

$docs = $storyTao->getDocsForTrack(array(1, 2, 4));
r(implode(';', array_keys($docs[1]))) && p() && e('1');
r(implode(';', array_keys($docs[2]))) && p() && e('2');
r($docs[1][1]) && p('id,title,addedBy,AID') && e('1,文档1,admin,1');
r($docs[2][2]) && p('id,title,addedBy,AID') && e('2,文档2,admin,2');
