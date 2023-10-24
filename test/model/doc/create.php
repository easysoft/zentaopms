#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
$doc = zdTable('doc');
$doc->id->range('1');
$doc->gen(0);

zdTable('user')->gen(5);
su('admin');

/**

title=测试 docModel->create();
cid=1
pid=1

创建产品文档 >> 新建产品文档
创建项目文档 >> 新建项目文档
创建执行文档 >> 新建执行文档
创建自定义文档 >> text,
创建Markdown文档 >> text,Markdown
创建私有文档 >> private
创建自定义文档 >> custom,1,2,3
不输入文档库 >> 『所属库』不能为空。
不输入标题 >> 『文档标题』不能为空。

*/
$libIDs = array('1', '117', '217', '821');
$type   = array('text', 'execution', 'custom', '');
$acl    = array('', 'open', 'custom', 'private');
$groups = array('1', '2', '3');
$users  = array('admin', 'dev1', 'dev10');

$createProductDoc   = array('lib' => $libIDs[0], 'title' => '新建产品文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$createProjectDoc   = array('lib' => $libIDs[1], 'title' => '新建项目文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$createExecutionDoc = array('lib' => $libIDs[2], 'title' => '新建执行文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$createCustomDoc    = array('lib' => $libIDs[3], 'title' => '新建自定义文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$createMarkdownDoc  = array('lib' => $libIDs[0], 'title' => '新建markdown文档', 'type' => $type[0], 'contentType' => 'markdown', 'contentMarkdown' => 'Markdown', 'acl' => $acl[1]);
$privateDoc         = array('lib' => $libIDs[0], 'title' => '新建私有文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[3]);
$customDoc          = array('lib' => $libIDs[0], 'title' => '新建自定义文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[2], 'groups' => $groups, 'users' => $users);
$noLib              = array('title' => '无文档库', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$noTitle            = array('lib' => $libIDs[0], 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$noType             = array('lib' => $libIDs[0], 'title' => '无TYPE', 'contentType' => 'html', 'acl' => $acl[1]);

$doc = new docTest();
r($doc->createTest($createProductDoc))   && p('0:title')      && e('新建产品文档');           //创建产品文档
r($doc->createTest($createProjectDoc))   && p('0:title')      && e('新建项目文档');           //创建项目文档
r($doc->createTest($createExecutionDoc)) && p('0:title')      && e('新建执行文档');           //创建执行文档
r($doc->createTest($createCustomDoc))    && p('0:type,draft') && e('text,');                  //创建自定义文档
r($doc->createTest($createMarkdownDoc))  && p('0:type,draft') && e('text,Markdown');          //创建Markdown文档
r($doc->createTest($privateDoc))         && p('0:acl')        && e('private');                //创建私有文档
r($doc->createTest($customDoc))          && p('0:acl,groups') && e('custom,1,2,3');           //创建自定义文档
r($doc->createTest($noLib))              && p('lib')          && e('『所属库』不能为空。');   //不输入文档库
r($doc->createTest($noTitle))            && p('title:0')      && e('『文档标题』不能为空。'); //不输入标题

