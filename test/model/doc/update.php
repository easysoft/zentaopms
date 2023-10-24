#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->update();
cid=1
pid=1



*/
$docID  = array('1', '2', '3', '4', '5', '6', '7', '8');
$libIDs = array('1', '117', '217', '821');
$type   = array('text', 'url', 'execution', 'custom', '');
$acl    = array('', 'open', 'custom', 'private');
$groups = array('1', '2', '3');
$users  = array('admin', 'dev1', 'dev10');

$updateProductDoc   = array('lib' => $libIDs[0], 'title' => '修改产品文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$updateProjectDoc   = array('lib' => $libIDs[1], 'title' => '修改项目文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$updateExecutionDoc = array('lib' => $libIDs[2], 'title' => '修改执行文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$updateCustomDoc    = array('lib' => $libIDs[3], 'title' => '修改自定义文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$updateUrlDoc       = array('lib' => $libIDs[0], 'title' => '修改URL文档', 'type' => $type[1], 'url' => 'www.baidu.com', 'acl' => $acl[1]);
$privateDoc         = array('lib' => $libIDs[0], 'title' => '修改私有文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[3]);
$customDoc          = array('lib' => $libIDs[0], 'title' => '修改自定义文档', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[2], 'groups' => $groups, 'users' => $users);
$noLib              = array('title' => '无文档库', 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$noTitle            = array('lib' => $libIDs[0], 'type' => $type[0], 'contentType' => 'html', 'acl' => $acl[1]);
$noType             = array('lib' => $libIDs[0], 'title' => '无TYPE', 'contentType' => 'html', 'acl' => $acl[1]);

$doc = new docTest();

r($doc->updateTest($docID[0], $updateProductDoc))   && p('1:field,old,new') && e('title,文档标题901,修改产品文档');//修改为产品文档
r($doc->updateTest($docID[1], $updateProjectDoc))   && p('1:field,old,new') && e('module,3622,0');                 //修改为项目文档
r($doc->updateTest($docID[2], $updateExecutionDoc)) && p('2:field,old,new') && e('title,文档标题903,修改执行文档');//修改为执行文档
r($doc->updateTest($docID[3], $updateCustomDoc))    && p('3:field,old,new') && e('keywords,关键词4,');             //修改为自定义文档
r($doc->updateTest($docID[4], $updateUrlDoc))       && p('4:field,old,new') && e('type,text,url');                 //修改为url文档
r($doc->updateTest($docID[6], $privateDoc))         && p('6:field,old,new') && e('acl,open,private');              //修改为私有文档
r($doc->updateTest($docID[7], $customDoc))          && p('7:field,old,new') && e('groups,1,1,2,3');                //修改为自定义文档
r($doc->updateTest($docID[0], $noLib))              && p('0:field,old,new') && e('lib,1,');                        //修改lib为空
r($doc->updateTest($docID[0], $noTitle))            && p('title:0')         && e('『文档标题』不能为空。');        //修改标题为空
r($doc->updateTest($docID[0], $noType))             && p('2:field,old,new') && e('type,text,');                    //修改type为空

