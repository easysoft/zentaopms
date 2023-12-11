#!/usr/bin/env php
<?php

/**

title=测试 docModel->insertSeperateDocs();
cid=1

- 创建产品文档第1条的title属性 @新建产品文档
- 创建项目文档第2条的title属性 @新建项目文档
- 创建执行文档第3条的title属性 @新建执行文档
- 创建自定义文档
 - 第4条的type属性 @text
- 创建私有文档第5条的acl属性 @private
- 创建自定义文档
 - 第6条的acl属性 @custom
 - 第6条的groups属性 @1,2,3
- 创建我的文档第7条的title属性 @新建我的文档
- 创建草稿文档
 - 第8条的title属性 @新建我的草稿文档
 - 第8条的status属性 @ draft
- 不输入文档库第lib条的0属性 @『所属库』不能为空。
- 不输入标题第title条的0属性 @『文档标题』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(30);
zdTable('doccontent')->gen(0);
zdTable('doc')->gen(0);
zdTable('user')->gen(5);
su('admin');

$libIDs = array(0, 26, 16, 21, 6, 11);
$acl    = array('open', 'custom', 'private');
$groups = '1,2,3';
$users  = 'admin,dev1,dev10';

$createProductDoc   = array('lib' => $libIDs[1], 'title' => '新建产品文档',     'acl' => $acl[0]);
$createProjectDoc   = array('lib' => $libIDs[2], 'title' => '新建项目文档',     'acl' => $acl[0]);
$createExecutionDoc = array('lib' => $libIDs[3], 'title' => '新建执行文档',     'acl' => $acl[0]);
$createCustomDoc    = array('lib' => $libIDs[4], 'title' => '新建自定义文档',   'acl' => $acl[1]);
$privateDoc         = array('lib' => $libIDs[4], 'title' => '新建私有文档',     'acl' => $acl[2]);
$customDoc          = array('lib' => $libIDs[5], 'title' => '新建自定义文档',   'acl' => $acl[1], 'groups' => $groups, 'users' => $users);
$mineDoc            = array('lib' => $libIDs[3], 'title' => '新建我的文档',     'acl' => $acl[0]);
$draftDoc           = array('lib' => $libIDs[3], 'title' => '新建我的草稿文档', 'acl' => $acl[0], 'status' => 'draft');
$noLib              = array('lib' => $libIDs[0], 'title' => '无文档库',         'acl' => $acl[0]);
$noTitle            = array('lib' => $libIDs[1], 'title' => '',                 'acl' => $acl[0]);

$docTester = new docTest();
r($docTester->insertSeperateDocsTest($createProductDoc))   && p('1:title')           && e('新建产品文档');            // 创建产品文档
r($docTester->insertSeperateDocsTest($createProjectDoc))   && p('2:title')           && e('新建项目文档');            // 创建项目文档
r($docTester->insertSeperateDocsTest($createExecutionDoc)) && p('3:title')           && e('新建执行文档');            // 创建执行文档
r($docTester->insertSeperateDocsTest($createCustomDoc))    && p('4:type')            && e('text,');                   // 创建自定义文档
r($docTester->insertSeperateDocsTest($privateDoc))         && p('5:acl')             && e('private');                 // 创建私有文档
r($docTester->insertSeperateDocsTest($customDoc))          && p('6:acl-groups', '-') && e('custom-1,2,3');            // 创建自定义文档
r($docTester->insertSeperateDocsTest($mineDoc))            && p('7:title')           && e('新建我的文档');            // 创建我的文档
r($docTester->insertSeperateDocsTest($draftDoc))           && p('8:title,status')    && e('新建我的草稿文档, draft'); // 创建草稿文档
r($docTester->insertSeperateDocsTest($noLib))              && p('lib:0')             && e('『所属库』不能为空。');    // 不输入文档库
r($docTester->insertSeperateDocsTest($noTitle))            && p('title:0')           && e('『文档标题』不能为空。');  // 不输入标题
