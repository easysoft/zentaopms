#!/usr/bin/env php
<?php

/**

title=测试 docModel->getEditedDocs();
timeout=0
cid=16171

- 获取按照id倒序排列我编辑过的文档
 - 第1条的title属性 @我的文档1
 - 第1条的editedBy属性 @admin
- 获取按照id正序排列我编辑过的文档
 - 第1条的title属性 @我的文档1
 - 第1条的editedBy属性 @admin
- 获取按照title正序排列我编辑过的文档
 - 第1条的title属性 @我的文档1
 - 第1条的editedBy属性 @admin
- 获取按照title倒序排列我编辑过的文档
 - 第1条的title属性 @我的文档1
 - 第1条的editedBy属性 @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$actionTable = zenData('action');
$actionTable->objectType->range('doc,story,task,bug');
$actionTable->objectID->range('1-20');
$actionTable->action->range('edited');
$actionTable->actor->range('admin,user1,user2');
$actionTable->gen(100);

zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$sorts = array('id_desc', 'id_asc', 'title_asc', 'title_desc');

$docTester = new docTaoTest();
r($docTester->getEditedDocsTest($sorts[0])) && p('1:title,editedBy') && e('我的文档1,admin'); // 获取按照id倒序排列我编辑过的文档
r($docTester->getEditedDocsTest($sorts[1])) && p('1:title,editedBy') && e('我的文档1,admin'); // 获取按照id正序排列我编辑过的文档
r($docTester->getEditedDocsTest($sorts[2])) && p('1:title,editedBy') && e('我的文档1,admin'); // 获取按照title正序排列我编辑过的文档
r($docTester->getEditedDocsTest($sorts[3])) && p('1:title,editedBy') && e('我的文档1,admin'); // 获取按照title倒序排列我编辑过的文档