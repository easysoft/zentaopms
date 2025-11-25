#!/usr/bin/env php
<?php

/**

title=测试 docModel->getTemplatesByType();
timeout=0
cid=16132

- 获取所有模板
 - 第1条的id属性 @1
 - 第1条的title属性 @产品模板1
 - 第1条的templateType属性 @PP
- 获取所有已发布模板
 - 第2条的id属性 @2
 - 第2条的title属性 @产品模板2
 - 第2条的templateType属性 @QAP
- 获取所有草稿模板
 - 第3条的id属性 @3
 - 第3条的title属性 @产品模板3
 - 第3条的templateType属性 @CMP
- 获取类型1下的所有模板
 - 第1条的id属性 @1
 - 第1条的title属性 @产品模板1
 - 第1条的templateType属性 @PP
- 获取类型1下的已发布模板
 - 第2条的id属性 @2
 - 第2条的title属性 @产品模板2
 - 第2条的templateType属性 @QAP
- 获取类型1下的草稿模板
 - 第3条的id属性 @3
 - 第3条的title属性 @产品模板3
 - 第3条的templateType属性 @CMP

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('module')->loadYaml('templatemodule')->gen(10);
zenData('user')->gen(5);
su('admin');

$types  = array(null, 1);
$status = array('all', 'normal', 'draft');

$docTester = new docTest();
r($docTester->getTemplatesByTypeTest($types[0], $status[0])) && p('1:id,title,templateType') && e('1,产品模板1,PP');  // 获取所有模板
r($docTester->getTemplatesByTypeTest($types[0], $status[1])) && p('2:id,title,templateType') && e('2,产品模板2,QAP'); // 获取所有已发布模板
r($docTester->getTemplatesByTypeTest($types[0], $status[2])) && p('3:id,title,templateType') && e('3,产品模板3,CMP'); // 获取所有草稿模板
r($docTester->getTemplatesByTypeTest($types[1], $status[0])) && p('1:id,title,templateType') && e('1,产品模板1,PP');  // 获取类型1下的所有模板
r($docTester->getTemplatesByTypeTest($types[1], $status[1])) && p('2:id,title,templateType') && e('2,产品模板2,QAP'); // 获取类型1下的已发布模板
r($docTester->getTemplatesByTypeTest($types[1], $status[2])) && p('3:id,title,templateType') && e('3,产品模板3,CMP'); // 获取类型1下的草稿模板
